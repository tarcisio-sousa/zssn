<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurvivorResource;
use Illuminate\Http\Request;
use App\Models\Survivor;
use App\Models\Inventory;
use App\Models\Resource;

class ApiSurvivorController extends Controller
{

    /**
     * List of survivor - get all registers
     * @param void
     * @return Objects
     */
    public function index() {
        return SurvivorResource::collection(Survivor::all());
    }

    /**
     * Register of survivor
     * @param id
     * @return Object
     */
    public function show($id) {
        return new SurvivorResource(Survivor::findOrFail($id));
    }

    /**
     * Get token to send request
     * @param Request
     * @return string
     */
    public function token(Request $request) {

        $token = $request->session()->token();

        $token = csrf_token();

        return $token;
    }

    /**
     * Create register survivor
     * @param Request
     * @return Survivor
     */
    public function store(Request $request) {
        $survivor = new Survivor;
        $survivor->name = $request->name;
        $survivor->age = $request->age;
        $survivor->gender = $request->gender;
        $survivor->longitude = $request->longitude;
        $survivor->latitude = $request->latitude;
        $survivor->infected = false;
        $survivor->save();

        foreach ($request->resources as $res) {
            $resource = new Resource;

            if (!$res['quantity']) {
                $res['quantity'] = 0;
            }

            $resource->survivor_id = $survivor->id;
            $resource->inventorie_id = $res['inventorie_id'];
            $resource->quantity = $res['quantity'];

            $resource->save();
        }

        return response()->json($survivor, 201);
    }

    /**
     * Update register survivor by id
     * @param Request
     * @param id
     * @return Survivor
     */
    public function update(Request $request, $id) {
        $survivor = Survivor::find($id)->update($request->all());

        return response()->json($survivor, 200);
    }

    /**
     * Destroy register survivor
     * @param id
     * @return null
     */
    public function destroy($id) {
        $survivor = Survivor::findOrFail($id);
        Resource::where('survivor_id', $survivor->id)->delete();
        $survivor->delete();

        return response()->json(null, 204);
    }

    /**
     * Mark survivor with infected
     * @param id
     * @return Survivor
     */
    public function mark_infected($id) {
        $survivor = Survivor::findOrFail($id);

        if ($survivor->infected < 3) {
            $survivor->infected = $survivor->infected + 1;
            $survivor->update();
            // return redirect('/')->with('msg', 'Sobrevivente marcado como infectado!');
        } else {
            // return redirect('/')->with('msg', 'Sobrevivente infectado!');
        }
        return $survivor;
    }

    public function count_infected_survivors() {
        return Survivor::where('infected', 3)->get()->count();
    }

    public function count_non_infected_survivors() {
        return Survivor::where('infected', '<', 3)->get()->count();
    }

    public function average_resources_survivors() {
        $resources = Resource::all();

        $inventories = Inventory::all();

        $nameInventory = array();

        foreach($inventories as $inventory) {
            $nameInventory[$inventory->id] = $inventory->item;
        }

        foreach($resources as $resource) {

            if (!isset($result[$resource->inventorie_id]['total'])) {
                $result[$resource->inventorie_id]['count'] = 0;
                $result[$resource->inventorie_id]['total'] = 0;
                $result[$resource->inventorie_id]['average'] = 0;
                $result[$resource->inventorie_id]['item'] = $nameInventory[$resource->inventorie_id];
            }

            $result[$resource->inventorie_id]['total'] += $resource->quantity;
            $result[$resource->inventorie_id]['count']++;
        }

        foreach($result as $i => &$r) {
            $r['average'] = round(($r['total'] / $r['count']), 2);
        }

        return $result;
    }

    /**
     * Report count - avg
     * @return Array 
     */
    public function report() {
        $total = Survivor::all()->count();
        $total_infected_survivors = $this->count_infected_survivors();
        $total_non_infected_survivors = $this->count_non_infected_survivors();
        $average_resources_survivors = $this->average_resources_survivors();

        $percentage_infected_survivors = round((($total_infected_survivors * 100) / $total), 2);
        $percentage_non_infected_survivors = round((($total_non_infected_survivors * 100) / $total), 2);

        return response()->json([
            'percentage_infected_survivors' => $percentage_infected_survivors,
            'percentage_non_infected_survivors' => $percentage_non_infected_survivors,
            'average_resources_survivors' => $average_resources_survivors
        ]);
    }

    public function trader($id) {
        $survivor = Survivor::findOrFail($id);

        foreach($survivor->resources as $resource) {
            $resource->inventory = Inventory::findOrFail($resource->inventorie_id);
        }

        return new SurvivorResource($survivor);
    }
}

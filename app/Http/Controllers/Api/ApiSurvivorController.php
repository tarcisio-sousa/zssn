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

    public function index() {
        return SurvivorResource::collection(Survivor::all());
    }

    public function show($id) {
        return new SurvivorResource(Survivor::findOrFail($id));
    }

    public function token(Request $request) {

        $token = $request->session()->token();

        $token = csrf_token();

        return $token;
    }

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

    public function update(Request $request, $id) {
        $survivor = Survivor::find($id)->update($request->all());

        return response()->json($survivor, 200);
    }

    public function destroy($id) {
        $survivor = Survivor::findOrFail($id);
        Resource::where('survivor_id', $survivor->id)->delete();
        $survivor->delete();

        return response()->json(null, 204);
    }

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

    public function trader($id) {
        $survivor = Survivor::findOrFail($id);

        foreach($survivor->resources as $resource) {
            $resource->inventory = Inventory::findOrFail($resource->inventorie_id);
        }

        return new SurvivorResource($survivor);
    }
}

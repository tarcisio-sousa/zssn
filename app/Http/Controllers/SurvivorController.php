<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survivor;
use App\Models\Inventory;
use App\Models\Resource;

class SurvivorController extends Controller {

    public function index() {
        $search = request('search');

        if ($search) {
            $survivors = Survivor::where([
                ['name', 'like', "%{$search}%"]
            ])->get();
        } else {
            $survivors = Survivor::all();
        }

        return view('welcome', [
            'survivors' => $survivors,
            'search' => $search
        ]);   
    }

    public function report() {
        return view('survivors.report');
    }

    public function show($id) {
        return view('survivors.show', [
            'survivor' => Survivor::findOrFail($id)
        ]);
    }

    public function create() {
        return view('survivors.create', [
            'inventory' => Inventory::all()
        ]);
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

        foreach ($request->resource as $id => $quantity) {
            $resource = new Resource;
            if (!$quantity) {
                $quantity = 0;
            }
            $resource->survivor_id = $survivor->id;
            $resource->inventorie_id = $id;
            $resource->quantity = $quantity;

            $resource->save();
        }

        return redirect('/')->with('msg', 'Sobrevivente cadastrado com sucesso!');
    }

    public function destroy($id) {
        Survivor::findOrFail($id)->delete();
        return redirect('/')->with('msg', 'Sobrevivente excluído com sucesso!');
    }

    public function edit($id) {
        $survivor = Survivor::findOrFail($id);
        return view('survivors.edit', ['survivor' => $survivor]);
    }

    public function update(Request $request) {
        Survivor::findOrFail($request->id)->update($request->all());
        return redirect('/')->with('msg', 'Sobrevivente atualizado com sucesso!');
    }

    public function edit_location($id) {
        $survivor = Survivor::findOrFail($id);
        return view('survivors.edit_location', ['survivor' => $survivor]);
    }

    public function mark_infected($survivor_infected_id) {
        $survivor = Survivor::findOrFail($survivor_infected_id);

        if ($survivor->infected < 3) {
            $survivor->infected = $survivor->infected + 1;
            $survivor->update();
            return redirect('/')->with('msg', 'Sobrevivente marcado como infectado!');
        } else {
            return redirect('/')->with('msg', 'Sobrevivente infectado!');
        }
    }

    public function trade($id) {
        $survivor = Survivor::findOrFail($id);
        $survivors_to = Survivor::where('id', '<>', $id)->where('infected', '<', 3)->get();

        $resources = $survivor->resources;
        foreach($resources as $resource) {
            $resource->inventory = Inventory::findOrFail($resource->inventorie_id);
        }

        return view('survivors.trade', [
            'survivor' => $survivor, 
            'resources' => $resources,
            'survivors_to' => $survivors_to
        ]);
    }

    public function do_trade(Request $request) {
        
        if ($request->total_survivor != $request->total_survivor_trade) {
            return redirect("/survivors/trade/{$request->survivor}")->with('msg', 'Pontuação não é suficiente para negociação!!!');
        }

        foreach($request->resource_survivor as $id => $quantity) {
            $survivorResource = Resource::where('survivor_id', $request->survivor)->where('inventorie_id', $id)->first();
            $survivorResource->quantity = $survivorResource->quantity - $quantity;
            $survivorResource->update();

            $survivorResourceTrade = Resource::where('survivor_id', $request->survivor_to_trade)->where('inventorie_id', $id)->first();
            if (!$survivorResourceTrade) {
                $survivorResourceTrade = new Resource;
                $survivorResourceTrade->survivor_id = $request->survivor_to_trade;
                $survivorResourceTrade->inventorie_id = $id;
                $survivorResourceTrade->quantity = $quantity;
                $survivorResourceTrade->save();
            } else {
                $survivorResourceTrade->quantity = $survivorResourceTrade->quantity + $quantity;
                $survivorResourceTrade->update();
            }
        }

        foreach($request->resource_survivor_trade as $id => $quantity) {
            $survivorResourceTrade = Resource::where('survivor_id', $request->survivor_to_trade)->where('inventorie_id', $id)->first();
            $survivorResourceTrade->quantity = $survivorResourceTrade->quantity - $quantity;
            $survivorResourceTrade->update();

            $survivorResource = Resource::where('survivor_id', $request->survivor)->where('inventorie_id', $id)->first();
            if (!$survivorResource) {
                $survivorResource = new Resource;
                $survivorResource->survivor_id = $request->survivor;
                $survivorResource->inventorie_id = $id;
                $survivorResource->quantity = $quantity;
                $survivorResource->save();
            } else {
                $survivorResource->quantity = $survivorResource->quantity + $quantity;
                $survivorResource->update();
            }
        }
        return redirect("/survivors/trade/{$request->survivor}")->with('msg', 'Negociação de recursos realizada com sucesso!!!');
    }

}

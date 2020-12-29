@extends('layouts.main')

@section('title', 'Negociar recursos de sobrevivência')

@section('content')

<script>

async function getData(url = '') {
    const response = await fetch(url, {
        method: 'GET',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        header: {
            'Content-Type': 'application/json'
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        // body: JSON.stringfy(data)
    })

    return response.json()
}

function removeSurvivorTrader() {
    let card_survivor_to_trade = document.getElementById('card_survivor_to_trade')
    let list_survivor_to_trade = document.getElementById('list_survivor_to_trade')
    card_survivor_to_trade.innerHTML = list_survivor_to_trade.innerHTML = ""
    document.getElementById('select_survivor_to').classList.remove('d-none')
    document.getElementById('select_survivor_to').value = ""
}

function selectSurvivorTo(elem) 
{
    let card_survivor_to_trade = document.getElementById('card_survivor_to_trade')
    let list_survivor_to_trade = document.getElementById('list_survivor_to_trade')
    
    if (!elem.value) {
        card_survivor_to_trade.innerHTML = list_survivor_to_trade.innerHTML = ""
        return
    }
    
    elem.classList.add('d-none')

    getData(`/api/survivor/trader/${elem.value}`)
        .then(response => {
            let survivor_to_trade = response.data
            card_survivor_to_trade.innerHTML = `
                <div class="card">
                    <div style="position: absolute;">
                        <button type="button" class="btn-close" onclick="removeSurvivorTrader()"></button>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <a href="/survivors/${survivor_to_trade.id}">${survivor_to_trade.name}</a>
                            <span>, ${survivor_to_trade.age}</span>
                            <div>
                                <small>${survivor_to_trade.gender}</small>
                            </div>
                            <input type="hidden" name="survivor_to_trade" value="${survivor_to_trade.id}" />
                        </div>
                    </div>
                </div>`
            list = `<ul class="list-group">`
            survivor_to_trade.resources.forEach(resource => {
                list += `
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-1">
                                <span id="item-trade-quantity-${resource.inventory.id}">${resource.quantity}</span>
                                </div>
                                <div class="col-md-8">${resource.inventory.item} - ${resource.inventory.points} Pontos</div>
                                <div class="col-md-3 text-center">
                                    <input type="number" name="resource_survivor_trade[${resource.inventory.id}]" value="0" class="form-control item-resource-survivor-trade" data-id="${resource.inventory.id}" data-quantity="${resource.quantity}" data-points="${resource.inventory.points}" onchange="calculateTotalSurvivorTrade(this)">
                                </div>
                            </div>
                        </li>
                `
            })
            list += `
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-9">TOTAL</div>
                        <div class="col-md-3"><input type="text" name="total_survivor_trade" value="0" id="total-survivor-trade" class="form-control disabled" /></div>
                    </div>
                </li>`
            
            list += `</ul>`
            list_survivor_to_trade.innerHTML = list

            verifyTotalResources()

        })
}

function verifyTotalResources() {
    if (!document.getElementById('total-survivor-trade')) return

    if (document.getElementById('total-survivor').value == document.getElementById('total-survivor-trade').value 
        && document.getElementById('total-survivor').value != 0) {
        document.getElementById('btn-submit-trade').classList.remove('d-none');
    } else {
        document.getElementById('btn-submit-trade').classList.add('d-none');
    }
}

function calculateTotalSurvivor(elem) {
    let itemsSurvivor = document.querySelectorAll('.item-resource-survivor')
    let totalSurvivor = document.getElementById('total-survivor')
    let quantityItem = document.getElementById(`item-quantity-${elem.dataset.id}`)

    calculateTotal(elem, itemsSurvivor, totalSurvivor, quantityItem)
}

function calculateTotalSurvivorTrade(elem) {
    let itemsSurvivor = document.querySelectorAll('.item-resource-survivor-trade')
    let totalSurvivor = document.getElementById('total-survivor-trade')
    let quantityItem = document.getElementById(`item-trade-quantity-${elem.dataset.id}`)

    calculateTotal(elem, itemsSurvivor, totalSurvivor, quantityItem)
}

function calculateTotal(elem, itemsSurvivor, totalSurvivor, quantityItem) {
    let total = 0
    elem_dataset_quantity = parseInt(elem.dataset.quantity)
    elem_value = parseInt(elem.value)

    if (elem_dataset_quantity >= elem.value) {
        quantityItem.innerHTML = elem_dataset_quantity - elem.value
        itemsSurvivor.forEach(item => {
            total += parseInt(item.value) * parseInt(item.dataset.points)
        })

        totalSurvivor.value = total

        verifyTotalResources()
    }
}

</script>

<h1>Negociação</h1>

<div class="row">
    <div class="col-md-12">
        <select name="survivor_to" id="select_survivor_to" class="form-select" onchange="selectSurvivorTo(this)">
            <option value="">Selecione um sobrevivente</option>

            @foreach($survivors_to as $survivor_to)
            <option value="{{ $survivor_to->id }}">{{ $survivor_to->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<hr>
<form action="/survivors/trade" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <a href="/survivors/{{ $survivor->id }}">{{ $survivor->name }}</a>
                        <span>, {{ $survivor->age }}</span>
                        @if($survivor->infected)
                        <span><ion-icon name="warning-outline"></ion-icon></span>
                        @endif
                        <div>
                            <small>{{ $survivor->gender }}</small>
                        </div>
                        <input type="hidden" name="survivor" value="{{ $survivor->id }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3" id="card_survivor_to_trade"></div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <ul class="list-group">
                @foreach($resources as $resource)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-1">
                            <span id="item-quantity-{{ $resource->inventory->id }}">{{ $resource->quantity }}</span>
                        </div>
                        <div class="col-md-8">
                            {{ $resource->inventory->item }} - {{ $resource->inventory->points }} Pontos
                        </div>
                        <div class="col-md-3 text-center">
                            <input type="number" name="resource_survivor[{{ $resource->inventory->id }}]" value="0" class="form-control item-resource-survivor" data-id="{{ $resource->inventory->id }}" data-quantity="{{ $resource->quantity }}" data-points="{{ $resource->inventory->points }}" onchange="calculateTotalSurvivor(this)">
                        </div>
                    </div>
                </li>
                @endforeach
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-9">TOTAL</div>
                        <div class="col-md-3"><input type="text" name="total_survivor" value="0" id="total-survivor" class="form-control disabled" /></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-6" id="list_survivor_to_trade">
        </div>
    </div>

    <div class="row mt-3">
        <div class="d-grid gap-2 col-2 mx-auto">
            <button type="submit" id="btn-submit-trade" class="btn btn-outline-success d-none"><ion-icon name="repeat-outline" style="font-size: 30px;"></ion-icon></button>
        </div>
    </div>
</form>
@endsection
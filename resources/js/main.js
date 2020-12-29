
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

function selectSurvivorTo(elem) 
{
    let card_survivor_to_trade = document.getElementById('card_survivor_to_trade')
    let list_survivor_to_trade = document.getElementById('list_survivor_to_trade')
    
    if (!elem.value) {
        card_survivor_to_trade.innerHTML = list_survivor_to_trade.innerHTML = "";
        return
    } 

    getData(`/api/survivor/trader/${elem.value}`)
        .then(response => {
            let survivor_to_trade = response.data
            card_survivor_to_trade.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="/survivors/${survivor_to_trade.id}">${survivor_to_trade.name}</a>
                            <span>, ${survivor_to_trade.age}</span>
                            <div>
                                <small>${survivor_to_trade.gender}</small>
                            </div>
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
                                    <input type="number" value="0" class="form-control item-resource-survivor-trade" data-id="${resource.inventory.id}" data-quantity="${resource.quantity}" data-points="${resource.inventory.points}" onchange="calculateTotalSurvivorTrade(this)">
                                </div>
                            </div>
                        </li>
                `
            })
            list += `
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-9">TOTAL</div>
                        <div class="col-md-3"><input type="text" name="total_survivor" value="0" id="total-survivor-trade" class="form-control disabled" /></div>
                    </div>
                </li>`
            
            list += `</ul>`
            list_survivor_to_trade.innerHTML = list

            verifyTotalResources()

        })
}

function verifyTotalResources() {
    if (!document.getElementById('total-survivor-trade')) return

    if (document.getElementById('total-survivor').value == document.getElementById('total-survivor-trade').value) {
        document.getElementById('btn-submit-trade').classList.remove('disabled');
    } else {
        document.getElementById('btn-submit-trade').classList.add('disabled');
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
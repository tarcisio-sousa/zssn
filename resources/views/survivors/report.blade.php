@extends('layouts.main')

@section('title', 'Zombie Network')

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
    })

    return response.json()
}

getData(`/api/survivors/report`)
    .then(response => {
        document.getElementById('percentage_infected_survivors').innerHTML = `${response.percentage_infected_survivors}%`
        document.getElementById('percentage_non_infected_survivors').innerHTML = `${response.percentage_non_infected_survivors}%`

        let cards = `<h2>Média de recursos por sobrevivente</h2>`;
        Object.keys(response.average_resources_survivors).forEach(key => {
            cards += `
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">${response.average_resources_survivors[key].item}</div>
                            <div class="text-center">${response.average_resources_survivors[key].average}</div>
                        </div>
                    </div>
                </div>`
        })
        document.getElementById('average_resources_survivors').innerHTML = cards

    })

</script>

<h1>Relatório! </h1>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Percentual de infectados</div>
                <div id="percentage_infected_survivors"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Percentual de não infectados</div>
                <div id="percentage_non_infected_survivors"></div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row" id="average_resources_survivors">
</div>

@endsection
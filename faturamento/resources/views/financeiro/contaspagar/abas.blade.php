<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
        <a id="geral" class="aba-financeiro nav-link active btn" href="#" style="color: blue;">Geral
            <span class="badge badge-primary badge-counter">{{$total}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="andamento" class="aba-financeiro nav-link text-warning btn" href="#">Em andamento
            <span class="badge badge-warning badge-counter">{{$qtdeAndamento}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="pendentes" class="aba-financeiro nav-link btn" href="#" style="color: red;">Pendentes
            <span class="badge badge-danger badge-counter">{{$qtdePendentes}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="pagas" class="aba-financeiro nav-link btn" href="#" style="color: green;">Pagas
            <span class="badge badge-success badge-counter">{{$qtdePagos}}</span>
        </a>
    </li>
</ul>

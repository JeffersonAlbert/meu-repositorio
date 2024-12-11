<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
        <a id="receber-geral" class="aba-switch nav-link active btn" href="{{ route('financeiro.pegar-abas') }}" style="color: blue;">Geral
            <span class="badge badge-primary badge-counter">{{ $geral }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="receber-pendentes" class="aba-switch nav-link btn" href="{{ route('financeiro.pegar-abas') }}" style="color: red;">Pendentes
            <span class="badge badge-danger badge-counter">{{ $pendente }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="receber-pagas" class="aba-switch nav-link btn" href="{{ route('financeiro.pegar-abas') }}" style="color: green;">Pagas
            <span class="badge badge-success badge-counter">{{ $pago }}</span>
        </a>
    </li>
</ul>
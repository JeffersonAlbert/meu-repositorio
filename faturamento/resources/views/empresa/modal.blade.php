<!-- Modal modal das filiais -->
<div class="modal fade" id="modalFiliais" tabindex="-1" role="dialog" aria-labelledby="modalFiliaisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFiliaisLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-responsive-xl">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Cpf/Cnpj</th>
                    <th scope="col">Razão Social</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody>
                @foreach($filiais as $filial)
                    <tr>
                        <th scope="row">{{$filial->id}}</th>
                        <td>{{$filial->cnpj}}</td>
                        <td>{{$filial->razao_social}}</td>
                        <td>
                            <a href="{{ route('filial.edit', ['filial' => $filial->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>
{{-- Modal para vizualizar os grupos da worflows --}}
<div class="modal fade" id="modalWorkflow" tabindex="-1" role="dialog" aria-labelledby="modalWorkflowLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalWorkflowLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            @if(isset($workflow[0]->w_id))
            <table class="table table-responsive-xl">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Workflow nome</th>
                    <th scope="col">Razão social</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody>
                @foreach($workflow as $flow)
                    <tr>
                        <th scope="row">{{$flow->w_id}}</th>
                        <td>{{$flow->w_nome}}</td>
                        <td>{{$flow->e_nome}}</td>
                        <td>
                            <a href="{{ $flow->w_id !== null ? route('workflow.edit', ['workflow' => $flow->w_id]) : null }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <a href="{{ route('workflow.create') }}">Nenhum workflow cadastrado, para adicionar clique aqui</a>
            @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>
{{-- Modal para vizualizar os grupos da empresa --}}
<div class="modal fade" id="modalGrupos" tabindex="-1" role="dialog" aria-labelledby="modalGruposLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGruposLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
             @if($grupos[0]->gp_id !== null)
            <table class="table table-responsive-xl">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Grupos nome</th>
                    <th scope="col">Razão social</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody>
                @foreach($grupos as $workflow)
                    <tr>
                        <th scope="row">{{$workflow->gp_id}}</th>
                        <td>{{$workflow->gp_nome}}</td>
                        <td>{{$workflow->nome}}</td>
                        <td>
                            <a href="{{ route('grupoprocesso.edit', ['grupoprocesso' => $workflow->gp_id ]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
             @else
                <a href="{{ route('grupoprocesso.create') }}">Nenhum grupo cadastrado, para adicionar clique aqui</a>
            @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>
{{-- Modal para vizualizar os usuarios da empresa --}}
<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUsuariosLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-responsive-xl">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Grupos nome</th>
                    <th scope="col">Razão social</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <th scope="row">{{$usuario->id}}</th>
                        <td>{{$usuario->name}} {{$usuario->last_name}}</td>
                        <td>{{$usuario->nome}}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', ['usuario' => $usuario->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>



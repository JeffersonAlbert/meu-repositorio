<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Number</title>
        <link rel="icon" href="{{ asset('img/static/favicon-wh.ico') }}" type="image/x-icon">
        <link href={{ asset("vendor/fontawesome-free/css/all.min.css") }}?{{ filemtime(public_path('vendor/fontawesome-free/css/all.min.css')) }} rel="stylesheet" type="text/css">
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
        {{-- <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet"> --}}
        @env('production')
            <link href='{{ asset("css/sb-admin-2.min.css") }}?v={{ filemtime(public_path('css/sb-admin-2.min.css')) }}' rel="stylesheet">
        @else
            <link href='{{ asset("css/sb-admin-2.css") }}?v={{ filemtime(public_path('css/sb-admin-2.css')) }}' rel="stylesheet">
        @endenv
        <link rel="stylesheet" href="{{ asset('css/bootstrap.icons.css') }}?{{ filemtime(public_path('css/bootstrap.icons.css')) }}">
    </head>
    <body id="page-top" @class(["sidebar-toogled"])>
        <div id="wrapper">
            <ul class="navbar-nav bg-gradient-number-wt sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('inicio') }}">
                    <div class="sidebar-brand-icon logo-number-wt mr-2">
                    </div>
                    <div class="logo-principal"></div>
                    <div class="sidebar-brand-text mx-3"><sup></sup></div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active inicio">
                    <a class="nav-link font-light-wt " href="{{ route('inicio') }}">
                        {{-- <i class="bi bi-grid" style="color: #7de1ac"></i> --}}
                        <div class="inicio active-number">
                            <i class="bi bi-inicio active-bi-number"></i>
                            <span class="font-regular-wt font-side-bar-menu">Início</span>
                        </div>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading menu financeiro-->
                <div class="sidebar-heading"#>
                    <div class="font-regular-wt font-heading-bar">
                        Financeiro:
                    </div>
                </div>
                <li class="nav-item contas-pagar">
                    <a class="nav-link" href="{{ route('contas-pagar.index') }}">
                        <div @class(['contas-pagar'])>
                            <i class="bi bi-compras"></i>
                            <span class="font-regular-wt font-menu-bar">Contas a pagar</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('acccounts-receivable.index') }}">
                        <div class="contas-receber">
                            <i class="bi bi-vendas"></i>
                            <span class="font-regular-wt font-menu-bar">Contas a receber</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendas.index') }}">
                        <i class="bi bi-vendas"></i>
                        <span class="font-regular-wt font-menu-bar">Vendas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contrato.index') }}">
                        <i class="bi bi-contrato-number"></i>
                        <span class="font-regular-wt font-menu-bar">Contratos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#relatorioMenu"
                       aria-expanded="true" aria-controls="setupMaster">
                        <i class="bi bi-relatorios"></i>
                        <span class="font-regular-wt font-menu-bar">Relatórios</span>
                    </a>
                    <div id="relatorioMenu" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-number py-2 collapse-inner rounded">
                            <a class="collapse-item" href="{{ route('relatorio.dre') }}">
                                <i class="bi bi-clipboard-pulse"></i>
                                <span class="">Dre</span>
                            </a>
                            <a @class(['collapse-item']) href="{{ route('financeiro.fluxo-caixa') }}">
                                <i @class(['bi bi-clipboard-pulse'])></i>
                                <span>Fluxo de caixa</span>
                            </a>
                            <a class="collapse-item" href="{{ route('relatorio.contas-receber') }}">
                                <i class="bi bi-clipboard-pulse"></i>
                                <span>Contas a receber</span>
                            </a>
                            <a @class(['collapse-item']) href="{{ route('relatorio.grid-contas-pagar') }}">
                                <i @class(['bi bi-clipboard-pulse'])></i>
                                <span>Contas a pagar</span>
                            </a>
                            <a class="collapse-item" href="{{ route('relatorio.index') }}">
                                <i class="bi bi-clipboard-pulse"></i>
                                <span class="">Relatórios</span>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('financeiro.dashboard') }}">
                        <i class="bi bi-dashboards"></i>
                        <span class="font-regular-wt font-menu-bar">Dashboards</span>
                    </a>
                </li>
                <!-- menu financeiro fim -->
                <div class="sidebar-divider"></div>
                <!-- menu GED -->
                <div class="sidebar-heading"#>
                    <div class="font-regular-wt font-heading-bar">
                        Processos:
                    </div>
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('payment-request.index') }}">
                        <div @class(['solicitacao'])>
                            <i class="bi bi-processos"></i>
                            <span class="font-regular-wt font-menu-bar">Solicitação de Pagamento</span>
                        </div>
                    </a>
                </li>
                <!-- menu GED fim -->
                <div class="sidebar-divider"></div>
                <!-- menu cadastro -->
                <div class="sidebar-heading"#>
                    <div class="font-regular-wt font-heading-bar">
                        Cadastros:
                    </div>
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('usuarios.index') }}">
                        <i class="bi bi-person-add-number"></i>
                        <span class="font-regular-wt font-menu-bar">Usuário</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fornecedor.index') }}">
                        <i class="bi bi-person-add-number"></i>
                        <span class="font-regular-wt font-menu-bar">Fornecedor</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('grupoprocesso.index') }}">
                        <i class="bi bi-groups-number"></i>
                        <span class="font-regular-wt font-menu-bar">Grupos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('workflow.index') }}">
                        <i class="bi bi-slider-number"></i>
                        <span class="font-regular-wt font-menu-bar">Workflow</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dre.index') }}">
                        <i class="bi bi-vendas"></i>
                        <span class="font-regular-wt font-menu-bar">Categoria financeiro</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ isset(auth()->user()->id_empresa) && !is_null(auth()->user()->id_empresa) ? route('empresa.show', ['empresa' => auth()->user()->id_empresa]) :  route('empresa.index')}}">
                        <i class="bi bi-building-gears-number"></i>
                        <span class="font-regular-wt font-menu-bar">Empresa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('produto.index') }}">
                        <i class="bi bi-open-box"></i>
                        <span class="font-regular-wt font-menu-bar">Produtos</span>
                    </a>
                </li>
                <!-- menu cadastro fim -->
                <div class="sidebar-divider"></div>
                <!-- menu configuracoes -->
                <div class="sidebar-heading"#>
                    <div class="font-regular-wt font-heading-bar">
                        Configurações:
                    </div>
                </div>
                @if(auth()->user()->administrator or auth()->user()->master)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('setup.create') }}">
                            <i class="bi bi-gears-number"></i>
                            <span class="font-regular-wt font-menu-bar">Configuração</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->master)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('forma-pagamento.index') }}">
                            <i class="bi bi-vendas"></i>
                            <span class="font-regular-wt font-menu-bar">Formas de pagamento</span>
                        </a>
                    </li>
                @endif
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

            </ul>
            <!-- Topbar Search -->
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <form
                            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <span class="font-regular-wt font-user-name-top-menu">Olá, {{ auth()->user()->name }} {{ auth()->user()->last_name }}</span>
                            </div>
                            <div class="input-group">
                                <span class="font-regular-wt font-user-date-info">{{ \App\Helpers\FormatUtils::now()->format('H:i, d M Y') }}</span>
                            </div>
                        </form>
                        <ul class="navbar-nav ml-auto">
                            <div class="col-5 mx-auto my-3 text-center">
                                <img id="dayNightImage" src="{{ asset('img/sun.svg') }}" style="width: 44px; height: 24px; margin-top: 7px;" class="d-inline-block">
                                <img src="{{ asset('img/White Mode.svg') }}" style="width: 70px; height: 18px; margin-top: 7px;" class="d-inline-block">
                            </div>


                            <div class="custom-control custom-switch" style="position: relative;">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" style="left: 1px; top: 24px;" for="customSwitch1"></label>
                            </div>

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                     aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                   placeholder="Search for..." aria-label="Search"
                                                   aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown access no-arrow mx-1" data-auto-close="false" id="code-line">

                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-alert"></i>

                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter badge-alert">3+</span>
                                </a>

                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list custom-dropdown dropdown-menu dropdown-menu-right shadow animated--grow-in" style="overflow-y: auto; max-height:80vh"
                                     aria-labelledby="alertsDropdown" id="alertsMessage">
                                    <h6 class="dropdown-header">
                                        Alerts Center
                                    </h6>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">X</a>
                                </div>
                            </li>


                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-message"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter">7</span>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                     aria-labelledby="messagesDropdown">
                                    <h6 class="dropdown-header backgound-rgba-0000-10">
                                        Message Center
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ asset('img/undraw_profile_1.svg') }}"
                                                 alt="...">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                                problem I've been having.</div>
                                            <div class="small text-gray-500">Emily Fowler · 58m</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ asset('img/undraw_profile_2.svg') }}"
                                                 alt="...">
                                            <div class="status-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">I have the photos that you ordered last month, how
                                                would you like them sent to you?</div>
                                            <div class="small text-gray-500">Jae Chun · 1d</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ asset('img/undraw_profile_3.svg') }}"
                                                 alt="...">
                                            <div class="status-indicator bg-warning"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">Last month's report looks great, I am very happy with
                                                the progress so far, keep up the good work!</div>
                                            <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src=""
                                                 alt="...">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                                told me that people say this to all dogs, even if they aren't good...</div>
                                            <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                                </div>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="img-profile rounded-circle"
                                         src="{{ auth()->user()->perfil_img !== null && file_exists(public_path('img/static/perfil/'.auth()->user()->perfil_img)) ?
                                        asset('img/static/perfil/'.auth()->user()->perfil_img) : asset('img/undraw_profile.svg') }}">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                     aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{route('usuarios.profile', ['id' => auth()->user()->id])}}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Activity Log
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->
                    <div class="container-fluid">
                        <div id="loader" class="loader">
                            Carregando...
                        </div>
                        <!-- captura de erros -->
                        <div class="mensagem-erro" style="display: none;"></div>
                        <div class="billboard" style="display: none;"></div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <p>{!! session('error') !!}</p>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">
                                <p>{!! session('success') !!}</p>
                            </div>
                        @endif
                        <!-- fim da captura de erros -->
                        {{ $slot }}
                    </div>
                </div>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; NumberTech</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </body>
    <footer>
        @stack('scripts')
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}?{{ time() }}"></script>
        <link href="{{ asset('js/select2/dist/css/select2.css') }}?{{ filemtime(public_path('js/select2/dist/css/select2.css')) }}" rel="stylesheet" />
        <script src="{{asset('js/select2/dist/js/select2.js')}}?{{ time() }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/sb-admin-2.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/dropdown-number.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/number-jquery.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/modal-number.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/number.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/functions-number.js') }}?{{ time() }}"></script>
        <script src="{{ asset('js/pdfjs/build/pdf.js') }}"></script>
        <script src="{{ asset('js/pdfjs/build/pdf.worker.js') }}"></script>
        <script src="{{ asset('js/pdfjs/web/viewer.js') }}"></script>
        <script src="{{ asset('js/openseadragon/openseadragon.js') }}"></script>
        <script type="text/javascript" src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
    </footer>
</html>

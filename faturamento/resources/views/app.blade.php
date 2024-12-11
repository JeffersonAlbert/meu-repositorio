<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Number</title>
    <link rel="icon" href="{{ asset('img/static/favicon-wh.ico') }}" type="image/x-icon">
    <!-- Custom fonts for this template-->
    <link href={{ asset("vendor/fontawesome-free/css/all.min.css") }} rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    @env('production')
    <link href={{ asset("css/sb-admin-2.min.css") }} rel="stylesheet">
    @else
    <link href={{ asset("css/sb-admin-2.css") }} rel="stylesheet">
    @endenv

    @vite('resources/js/app.js')
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
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
            <li class="nav-item active">
                <a class="nav-link font-light-wt " href="{{ route('inicio') }}">
                    {{-- <i class="bi bi-grid" style="color: #7de1ac"></i> --}}
                    <div class="active-number">
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
            <li class="nav-item">
                <a class="nav-link" href="{{ route('financeiro.controle') }}">
                    <i class="bi bi-compras"></i>
                    <span class="font-regular-wt font-menu-bar">Contas a pagar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('financeiro.receber') }}">
                    <i class="bi bi-vendas"></i>
                    <span class="font-regular-wt font-menu-bar">Contas a receber</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('contrato.index') }}">
                    <i class="bi bi-contrato-number"></i>
                    <span class="font-regular-wt font-menu-bar">Contratos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('relatorio.index') }}">
                    <i class="bi bi-relatorios"></i>
                    <span class="font-regular-wt font-menu-bar">Relatórios</span>
                </a>
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
                    <i class="bi bi-processos"></i>
                    <span class="font-regular-wt font-menu-bar">Solicitação de Pagamento </span>
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
            <!-- menu configuracaoes -->
            <!-- Nav Item - Pages Collapse Menu -->

            {{-- controle financeiro--}}
            {{--  <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#financeiroMenu"
                    aria-expanded="true" aria-controls="setupMaster">
                    <i class="bi bi-compras"></i>
                    <span class="font-regular-wt font-menu-bar">Controle Financeiro</span>
                </a>
                <div id="financeiroMenu" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Financeiro:</h6>
                        <a class="collapse-item" href="{{ route('financeiro.controle') }}">Contas a pagar</a>
                        <a class="collapse-item" href="{{ route('financeiro.receber') }}">Contas a receber</a>
                        <a class="collapse-item" href="{{ route('contrato.index') }}">Contrato</a>
                        <a class="collapse-item" href="{{ route('financeiro.dashboard') }}">Dashboard financeiro</a>
                    </div>
                </div>
            </li> --}}

            {{--<li class="nav-item">
                <a class="nav-link" href="{{ route('financeiro.controle') }}">
                    <i class="bi bi-currency-dollar"></i>
                    <span>Controle financeiro</span></a>
            </li>--}}
            <!-- Nav Item - Centro de custos -->
            {{--  <li class="nav-item">
             <a class="nav-link" href="{{ route('centrocusto.index') }}">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Centro de custo</span></a>
            </li> --}}
            <!-- Nav Item - Processo faturamnto -->


            <!-- Nav Item - Empresa -->
            {{--  @if(auth()->user()->master)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('empresa.index') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Empresa</span>
                </a>
            </li>
            @endif --}}
            <!-- Nav Item - Setup -->
            {{-- @if(auth()->user()->administrator and !auth()->user()->master)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('setup.create') }}">
                    <i class="bi bi-house-gear-fill"></i>
                    <span>Configuração</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->master)
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#setupMaster"
                    aria-expanded="true" aria-controls="setupMaster">
                    <i class="bi bi-house-gear-fill"></i>
                    <span>Configuração</span>
                </a>
                <div id="setupMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Setup:</h6>
                        <a class="collapse-item" href="{{ route('setup.create') }}">Configuração pagamento/recebimento</a>
                        <a class="collapse-item" href="{{ route('forma-pagamento.index') }}">Forma de pagamento</a>
                    </div>
                </div>
            </li>
            @endif --}}
            <!-- Nav Item - Prolapse cadastros -->
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#cadastros"
                    aria-expanded="true" aria-controls="cadastros">
                    <i class="bi bi-journal-check"></i>
                    <span>Cadastros</span>
                </a>
                <div id="cadastros" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="{{ route('usuarios.index') }}">Usuarios</a>
                        <a class="collapse-item" href="{{ route('fornecedor.index') }}">Fornecedor</a>
                        <a class="collapse-item" href="{{ isset(auth()->user()->id_empresa) && !is_null(auth()->user()->id_empresa) ? route('empresa.show', ['empresa' => auth()->user()->id_empresa]) :  route('empresa.index')}}">Empresa</a>
                        <a class="collapse-item" href="{{ route('grupoprocesso.index') }}">Grupos</a>
                        <a class="collapse-item" href="{{ route('workflow.index') }}">Work Flow</a>
                    </div>
                </div>
            </li> --}}


            <!-- Nav Item - Prolapse componets -->
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> --}}

            <!-- Nav Item - Utilities Collapse Menu -->
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> --}}

            <!-- Divider -->
            {{-- <hr class="sidebar-divider"> --}}

            <!-- Heading -->
            {{-- <div class="sidebar-heading">
                Addons
            </div> --}}

            <!-- Nav Item - Pages Collapse Menu -->
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li> --}}

            <!-- Nav Item - Charts -->
            {{-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> --}}
            <!-- Nav Item - Tables -->
            {{-- <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> --}}

            <!-- Divider -->
            {{-- <hr class="sidebar-divider d-none d-md-block"> --}}

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            {{-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src={{asset("img/undraw_rocket.svg")}} alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> --}}

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <span class="font-regular-wt font-user-name-top-menu">Olá, {{ auth()->user()->name }} {{ auth()->user()->last_name }}</span>
                            {{-- <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div> --}}
                        </div>
                        <div class="input-group">
                            <span class="font-regular-wt font-user-date-info">{{ \App\Helpers\FormatUtils::now()->format('H:i, d M Y') }}</span>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
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
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- tag do evento de loader -->
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
                    <!-- Page Heading -->


                    <!-- seu codigo vai comecar aqui -->
                    <div id='informativo' class="row" >
                        <div class="col-9">
                            <div class="card card-info">
                               <button id="closeInfo" class="close-button" title="Fechar"><span class="close-text">x</span></button>

                                <div class="row">
                                    <div class="col-5">
                                        <div class="row m-3">
                                            <span class="font-regular-wt titulo-novidade ml-3">Novidade</span>
                                        </div>
                                        <div class="row m-3">
                                            <span class="font-regular-wt texto-novidade ml-3">
                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                            </span>
                                        </div>
                                        <div class="row mt-3 ml-3">
                                            <button class="ml-3 btn btn-sm btn-success btn-success-number font-regular-wt">Saiba mais</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-2">
                                                <img class="" style="margin-top: 190%;" src="{{ url('/img/static/icons/moca_pc.png') }}" alt="Descrição da Imagem">
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-2"></div>
                                            <div class="col-2">
                                                <img class="moco_pc" src="{{ url('/img/static/icons/moco_pc.png') }}" alt="Descrição da Imagem">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-0"></div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card card-graphs">
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="ml-3 row">
                                            <span class="font-regular-wt ml-3 mt-3 font-avisos">Avisos</span>
                                        </div>
                                        <div class="ml-3 row">
                                            <span class="font-regular-wt mt-3 ml-3">Gráficos dinâmicos e completo</span>
                                        </div>
                                        <div class="ml-3 row">
                                            <button class="mt-3 ml-3 mb-3 btn btn-sm btn-success btn-success-number">Saiba mais</button>
                                        </div>
                                        <div class="mb-3"></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <img class="mt-3 mb-3 img-info-graficos" src="{{ url('/img/static/icons/grafico.png') }}" alt="Descrição da Imagem">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-3">
                        <div class="col-1">
                            <a href="{{ url()->previous() }}" class="btn btn-success btn-back-number">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    @inertia
                    <div class="row m-3">
                        <div class="col-1">
                            <a href="{{ url()->previous() }}" class="btn btn-success btn-back-number">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <!-- seu codigo vai terminar aqui -->

                    <!-- Content Row -->
                    <!-- <div class="row"> -->

                        <!-- Earnings (Monthly) Card Example -->
                        <!-- <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Earnings (Monthly) Card Example -->
                        <!-- <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Earnings (Monthly) Card Example -->
                        <!-- <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Pending Requests Card Example -->
                        <!-- <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <!-- </div> -->

                    <!-- Content Row -->

                    <!-- <div class="row"> -->

                        <!-- Area Chart -->
                        <!-- <div class="col-xl-8 col-lg-7"> -->
                            <!-- <div class="card shadow mb-4"> -->
                                <!-- Card Header - Dropdown -->
                                <!-- <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- Card Body -->
                                <!-- <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div> -->
                            <!-- </div> -->
                        <!-- </div> -->

                        <!-- Pie Chart -->
                        <!-- <div class="col-xl-4 col-lg-5"> -->
                            <!-- <div class="card shadow mb-4"> -->
                                <!-- Card Header - Dropdown -->
                                <!--<div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- Card Body -->
                                <!--<div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>-->
                            <!-- </div> -->
                        <!-- </div> -->
                    <!-- </div> -->

                    <!-- Content Row -->
                    <!-- <div class="row"> -->

                        <!-- Content Column -->
                        <!-- <div class="col-lg-6 mb-4"> -->

                            <!-- Project Card Example -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Server Migration <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales Tracking <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Color System -->
                            <!-- <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            Primary
                                            <div class="text-white-50 small">#4e73df</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            Success
                                            <div class="text-white-50 small">#1cc88a</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Info
                                            <div class="text-white-50 small">#36b9cc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            Warning
                                            <div class="text-white-50 small">#f6c23e</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Danger
                                            <div class="text-white-50 small">#e74a3b</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-secondary text-white shadow">
                                        <div class="card-body">
                                            Secondary
                                            <div class="text-white-50 small">#858796</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-light text-black shadow">
                                        <div class="card-body">
                                            Light
                                            <div class="text-black-50 small">#f8f9fc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-dark text-white shadow">
                                        <div class="card-body">
                                            Dark
                                            <div class="text-white-50 small">#5a5c69</div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        <!-- </div> -->

                        <!-- <div class="col-lg-6 mb-4"> -->

                            <!-- Illustrations -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="{{ asset('img/undraw_posting_photo.svg') }}" alt="...">
                                    </div>
                                    <p>Add some quality, svg illustrations to your project courtesy of <a
                                            target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                        constantly updated collection of beautiful svg images that you can use
                                        completely free and without attribution!</p>
                                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                        unDraw &rarr;</a>
                                </div>
                            </div> -->

                            <!-- Approach -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                                </div>
                                <div class="card-body">
                                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                        CSS bloat and poor page performance. Custom CSS classes are used to create
                                        custom components and custom utility classes.</p>
                                    <p class="mb-0">Before working with this theme, you should become familiar with the
                                        Bootstrap framework, especially the utility classes.</p>
                                </div>
                            </div> -->

                        <!-- </div> -->
                    <!-- </div> -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pronto para sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" title="Fechar">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione "Sair" abaixo se estiver pronto para encerrar sua sessão atual.</div>
                <div class="modal-footer">
                    <button class="btn btn-info btn-back-number" type="button" data-dismiss="modal">Voltar</button>
                        <a class="btn btn-success btn-back-number-saida" style="padding: 6px 20px 6px 20px; border-radius: 6px" href="{{ route('signout') }}">Sair</a>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->

    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <!-- javascript para busca interna api goole -->
    <!-- javascript type ahead -->
    <script src="{{ asset('js/bootstrap3-typeahead.min.js') }}"></script>
    <!-- tags input js -->
    <script src="{{ asset('js/tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/pdfjs/build/pdf.js') }}"></script>
    {{-- scripts gerais para uso no number--}}


    <script src="{{ asset('js/number.js') }}"></script>
    <script src="{{ asset('js/number-01.js') }}"></script>
    <script src="{{ asset('js/functions-number.js') }}"></script>
    {{-- <script src="{{ asset('js/wt-dt-switch.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.icons.css') }}">
    @include('layout.loader')
    @include('layout.alerts')
    @include('layout.jsFunctions')
    @include('layout.switch-dt-wt')
</body>

</html>

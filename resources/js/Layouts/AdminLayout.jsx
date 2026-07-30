import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AdminLayout({ children }) {
    const { url, props } = usePage();
    const { auth, stats } = props;
    const pendingCount = stats?.pendingOrders || 0;
    const [theme, setTheme] = useState('dark');

    useEffect(() => {
        const savedTheme = localStorage.getItem('app-theme') || 'dark';
        setTheme(savedTheme);
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    }, []);

    const toggleTheme = () => {
        const newTheme = theme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        localStorage.setItem('app-theme', newTheme);
        document.documentElement.setAttribute('data-bs-theme', newTheme);
    };

    const isAdmin = auth?.roles?.includes('Administrador');
    const isRecepcion = auth?.roles?.includes('Recepcionista');
    const isMecanico = auth?.roles?.includes('Mecanico');

    const menuItems = [
        { name: 'Dashboard', path: '/dashboard', icon: 'bi-grid-fill', show: isAdmin || isRecepcion },
        { name: 'Pizarra de Órdenes', path: '/taller/kanban', icon: 'bi-kanban-fill', show: true }, // Todos ven esto
        { name: 'Catálogo', path: '/inventario/productos', icon: 'bi-box-fill', show: isAdmin },
        { name: 'Catálogo Servicios', path: '/taller/servicios', icon: 'bi-wrench-adjustable', show: isAdmin },
        { name: 'Precios', path: '/inventario/precios', icon: 'bi-tags-fill', show: isAdmin },
        { name: 'Kardex', path: '/inventario/kardex', icon: 'bi-box-seam-fill', show: isAdmin || isMecanico },
        { name: 'Finanzas', path: '/reportes/dashboard', icon: 'bi-pie-chart-fill', show: isAdmin },
        { name: 'Portal Cliente', path: '/seguimiento', icon: 'bi-globe', show: true },
    ].filter(item => item.show);

    return (
        <div className="d-flex min-vh-100" style={{ backgroundColor: 'var(--bg-dark)' }}>
            
            {/* Sidebar Lateral */}
            <aside className="d-flex flex-column flex-shrink-0 p-3" style={{ width: '260px', backgroundColor: 'var(--bg-panel)', borderRight: '1px solid var(--border-light)' }}>
                <a href="/" className="d-flex align-items-center mb-4 text-white text-decoration-none px-2">
                    <i className="bi bi-heptagon-fill fs-3 text-primary-accent me-2"></i>
                    <span className="fs-5 fw-bold tracking-wider">Taller<span className="text-primary-accent">Pro</span></span>
                </a>
                
                <hr className="text-secondary opacity-25 mt-0 mb-4" />
                
                <ul className="nav nav-pills flex-column mb-auto gap-2">
                    {menuItems.map(item => {
                        const isActive = url.startsWith(item.path) || url === item.path;
                        return (
                            <li className="nav-item" key={item.name}>
                                <Link 
                                    href={item.path} 
                                    className={`nav-link d-flex align-items-center ${isActive ? 'bg-primary-accent shadow-sm' : 'text-white opacity-75'}`}
                                    style={{ borderRadius: '10px', transition: 'all 0.2s' }}
                                >
                                    <i className={`bi ${item.icon} me-3 fs-5 ${isActive ? 'text-white' : 'text-muted'}`}></i>
                                    <span className="fw-semibold">{item.name}</span>
                                </Link>
                            </li>
                        );
                    })}
                </ul>
                
                <hr className="text-secondary opacity-25" />
                
                <div className="dropdown">
                    <a href="#" className="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <div className="rounded-circle bg-secondary bg-opacity-25 d-flex justify-content-center align-items-center me-2" style={{ width: '32px', height: '32px' }}>
                            <i className="bi bi-person-fill"></i>
                        </div>
                        <strong>{auth?.user?.name || 'Admin User'}</strong>
                    </a>
                    <ul className="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                        <li><span className="dropdown-item text-muted small">{auth?.roles?.[0] || 'Sin Rol'}</span></li>
                        <li><hr className="dropdown-divider" /></li>
                        <li><Link className="dropdown-item" href="/taller/kanban">Modo Kiosco</Link></li>
                        <li><hr className="dropdown-divider" /></li>
                        <li><Link className="dropdown-item text-danger" href="/logout" method="post" as="button">Cerrar Sesión</Link></li>
                    </ul>
                </div>
            </aside>

            {/* Contenido Principal */}
            <main className="flex-grow-1 overflow-auto d-flex flex-column">
                
                {/* Header Superior Delgado */}
                <header className="px-4 py-3 d-flex justify-content-between align-items-center" style={{ borderBottom: '1px solid var(--border-light)', backgroundColor: 'var(--bg-panel)' }}>
                    <div>
                        <span className="text-muted small fw-semibold text-uppercase tracking-wider">Sistema Operativo v1.0</span>
                    </div>
                    <div className="d-flex align-items-center gap-3">
                        <button 
                            onClick={toggleTheme} 
                            className="btn btn-sm btn-outline-secondary border-0 text-muted"
                            title={theme === 'dark' ? "Cambiar a modo claro" : "Cambiar a modo oscuro"}
                        >
                            <i className={`bi ${theme === 'dark' ? 'bi-sun' : 'bi-moon-fill'} fs-5`}></i>
                        </button>
                        <div className="position-relative ms-2 dropdown">
                            <a href="#" className="text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <i className={`bi bi-bell fs-5 ${pendingCount > 0 ? 'text-warning' : 'text-muted'}`}></i>
                                {pendingCount > 0 && (
                                    <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style={{ fontSize: '0.65rem' }}>
                                        {pendingCount}
                                    </span>
                                )}
                            </a>
                            <ul className="dropdown-menu dropdown-menu-end shadow" style={{ minWidth: '250px' }}>
                                <li><h6 className="dropdown-header">Notificaciones</h6></li>
                                {pendingCount > 0 ? (
                                    <>
                                        <li>
                                            <div className="dropdown-item d-flex align-items-center py-2">
                                                <i className="bi bi-exclamation-circle-fill text-warning me-2"></i>
                                                <span className="text-wrap">Hay <strong>{pendingCount}</strong> pedido(s) pendiente(s) de revisión.</span>
                                            </div>
                                        </li>
                                        <li><hr className="dropdown-divider" /></li>
                                        <li><Link href="/taller/kanban" className="dropdown-item text-center text-primary fw-bold">Ver Órdenes Pendientes</Link></li>
                                    </>
                                ) : (
                                    <li><span className="dropdown-item text-muted text-center py-3">No hay notificaciones nuevas</span></li>
                                )}
                            </ul>
                        </div>
                        <form action="/logout" method="POST" className="m-0">
                            <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')} />
                            <Link href="/logout" method="post" as="button" className="btn btn-sm btn-outline-secondary border-0 text-muted">
                                <i className="bi bi-box-arrow-right fs-5"></i>
                            </Link>
                        </form>
                    </div>
                </header>

                {/* Render de las páginas dinámicas */}
                <div className="flex-grow-1 p-4 p-md-5">
                    {children}
                </div>
                
                <footer className="text-center py-3 text-muted small border-top" style={{ borderColor: 'var(--border-light)' }}>
                    &copy; {new Date().getFullYear()} Taller Automotriz Pro - Panel de Administración
                </footer>
            </main>
        </div>
    );
}

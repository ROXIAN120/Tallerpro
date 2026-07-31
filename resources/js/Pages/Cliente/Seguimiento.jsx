import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';

export default function Seguimiento({ ordenData, error }) {
    const { data, setData, post, processing } = useForm({
        placa: ''
    });

    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const submit = (e) => {
        e.preventDefault();
        if (!data.placa.trim()) return;
        post('/seguimiento', {
            preserveState: true,
            preserveScroll: true
        });
    };

    const getTimelineClass = (step, currentProgress) => {
        const stepProgress = step === 'PENDIENTE' ? 0 : step === 'EN_PROCESO' ? 50 : 100;
        if (currentProgress > stepProgress) return 'bg-success text-white border-success shadow-lg scale-up';
        if (currentProgress === stepProgress) return 'bg-primary-accent text-white border-primary-accent shadow-lg pulse-glow scale-up';
        return 'bg-dark text-muted border-secondary';
    };

    return (
        <div className="min-vh-100 d-flex flex-column" style={{ backgroundColor: '#0a0a0f', color: '#e2e8f0', fontFamily: "'Inter', sans-serif" }}>
            <Head title="Seguimiento Premium | Taller Pro" />

            {/* Navbar Sticky */}
            <nav className={`navbar fixed-top transition-all ${scrolled ? 'py-2 shadow-lg' : 'py-4'}`} style={{ backgroundColor: scrolled ? 'rgba(10, 10, 15, 0.85)' : 'transparent', backdropFilter: scrolled ? 'blur(12px)' : 'none', borderBottom: scrolled ? '1px solid rgba(255,255,255,0.05)' : 'none', zIndex: 1030 }}>
                <div className="container">
                    <a className="navbar-brand d-flex align-items-center gap-2" href="#">
                        <div className="bg-primary-accent text-white rounded p-2 d-flex align-items-center justify-content-center" style={{ width: '40px', height: '40px' }}>
                            <i className="bi bi-heptagon-fill fs-4"></i>
                        </div>
                        <span className="fs-4 fw-bold text-white tracking-wider">Taller<span className="text-primary-accent">Pro</span></span>
                    </a>
                    <div className="d-none d-md-flex gap-4 fw-semibold small text-uppercase tracking-wider">
                        <a href="#buscar" className="text-white text-decoration-none nav-link-custom">Seguimiento</a>
                        <a href="#servicios" className="text-white text-decoration-none nav-link-custom">Servicios</a>
                        <a href="#contacto" className="text-white text-decoration-none nav-link-custom">Ubicación</a>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section id="buscar" className="position-relative d-flex align-items-center justify-content-center overflow-hidden pt-5" style={{ minHeight: '80vh', background: 'radial-gradient(circle at top right, rgba(0, 158, 247, 0.15), transparent 50%), radial-gradient(circle at bottom left, rgba(15, 23, 42, 0.8), #0a0a0f 80%)' }}>
                {/* Decoración de fondo */}
                <div className="position-absolute top-0 start-0 w-100 h-100" style={{ backgroundImage: 'radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px)', backgroundSize: '30px 30px', opacity: 0.5 }}></div>

                <div className="container position-relative z-index-2 pt-5 mt-5">
                    <div className="row justify-content-center">
                        <div className="col-lg-8 text-center slide-up-animation">
                            <span className="badge bg-primary-accent bg-opacity-10 text-primary-accent px-3 py-2 rounded-pill mb-4 border border-primary-accent border-opacity-25 fw-semibold tracking-wider">
                                <i className="bi bi-broadcast me-2"></i>SISTEMA EN TIEMPO REAL
                            </span>
                            <h1 className="display-4 fw-bolder text-white mb-4 lh-sm">
                                El estado de tu vehículo, <br className="d-none d-md-block" />
                                <span className="text-transparent bg-clip-text bg-gradient-primary">A un clic de distancia</span>
                            </h1>
                            <p className="lead text-muted mb-5 px-md-5">
                                Ingresa la placa de tu vehículo y descubre exactamente en qué etapa del mantenimiento nos encontramos. Transparencia total, sin llamadas ni demoras.
                            </p>

                            {/* Buscador Premium */}
                            <div className="glass-panel p-2 rounded-pill mx-auto mb-5 position-relative shadow-glow" style={{ maxWidth: '600px', border: '1px solid rgba(255,255,255,0.1)', background: 'rgba(25, 30, 40, 0.6)' }}>
                                <form onSubmit={submit} className="d-flex align-items-center">
                                    <div className="ps-4 pe-2 text-muted">
                                        <i className="bi bi-car-front fs-5"></i>
                                    </div>
                                    <input
                                        type="text"
                                        className="form-control bg-transparent border-0 text-white text-uppercase fs-5 fw-bold search-input"
                                        placeholder="EJ: ABC-123"
                                        value={data.placa}
                                        onChange={e => setData('placa', e.target.value.toUpperCase())}
                                        disabled={processing}
                                        style={{ boxShadow: 'none' }}
                                    />
                                    <button
                                        className="btn bg-primary-accent text-white fw-bold rounded-pill px-4 py-3 ms-2 d-flex align-items-center gap-2 hover-lift"
                                        type="submit"
                                        disabled={processing}
                                    >
                                        {processing ? <span className="spinner-border spinner-border-sm" role="status"></span> : <><i className="bi bi-search"></i> <span className="d-none d-sm-inline">Rastrear</span></>}
                                    </button>
                                </form>
                            </div>

                            {error && (
                                <div className="alert bg-danger bg-opacity-10 border border-danger text-danger py-3 rounded-4 mx-auto fade-in" style={{ maxWidth: '600px' }}>
                                    <i className="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                                    <span className="fw-semibold">{error}</span>
                                </div>
                            )}

                        </div>
                    </div>

                    {/* Resultado Expandido */}
                    {ordenData && (
                        <div className="row justify-content-center mt-4 slide-up-animation" style={{ animationDelay: '0.2s' }}>
                            <div className="col-lg-10">
                                <div className="glass-panel p-0 rounded-4 overflow-hidden border-primary border-opacity-50 shadow-glow-lg">
                                    {/* Cabecera del Resultado */}
                                    <div className="p-4 p-md-5 bg-gradient-dark-blue d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                                        <div>
                                            <span className="text-primary-accent small fw-bold tracking-wider text-uppercase d-block mb-1">Orden de Trabajo #{ordenData.id}</span>
                                            <h2 className="text-white fw-bolder mb-0 display-6">{ordenData.placa}</h2>
                                        </div>
                                        <div className="text-center text-md-end">
                                            <div className="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-4 py-2 rounded-pill fs-6 fw-normal mb-2">
                                                <i className="bi bi-person-circle me-2"></i>{ordenData.cliente}
                                            </div>
                                            <p className="text-muted small mb-0"><i className="bi bi-calendar3 me-1"></i> Registrado: {ordenData.fecha}</p>
                                        </div>
                                    </div>

                                    {/* Progreso */}
                                    <div className="p-4 p-md-5 bg-panel-dark">
                                        <div className="text-center mb-5">
                                            <h5 className="text-muted fw-normal mb-2">Servicio Actual</h5>
                                            <h3 className="text-white fw-bold bg-clip-text text-transparent bg-gradient-primary d-inline-block mb-3">{ordenData.servicio}</h3>
                                            <div>
                                                <span className="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill fs-6">
                                                    <i className="bi bi-stopwatch me-2 text-primary-accent"></i>
                                                    {ordenData.tiempo_estimado > 0 
                                                        ? `Tiempo estimado: ${ordenData.tiempo_estimado} ${ordenData.tiempo_estimado === 1 ? 'hora' : 'horas'}` 
                                                        : 'Tiempo indefinido'}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="position-relative mt-5 mb-4 px-3 px-md-5">
                                            <div className="position-absolute top-50 start-0 w-100 bg-secondary opacity-25 rounded-pill" style={{ height: '6px', transform: 'translateY(-50%)', zIndex: 1, margin: '0 10%' }}></div>
                                            <div className="position-absolute top-50 start-0 bg-primary-accent rounded-pill glow-bar" style={{ height: '6px', width: `calc(${ordenData.progreso}% * 0.8 + 10%)`, transform: 'translateY(-50%)', zIndex: 2, transition: 'width 1.5s cubic-bezier(0.4, 0, 0.2, 1)' }}></div>

                                            <div className="d-flex justify-content-between position-relative" style={{ zIndex: 3 }}>
                                                {/* Paso 1 */}
                                                <div className="text-center d-flex flex-column align-items-center" style={{ width: '80px' }}>
                                                    <div className={`rounded-circle d-flex align-items-center justify-content-center mb-3 border border-4 transition-all ${getTimelineClass('PENDIENTE', ordenData.progreso)}`} style={{ width: '60px', height: '60px', backgroundColor: '#1e1e2d' }}>
                                                        <i className="bi bi-clipboard-check fs-3"></i>
                                                    </div>
                                                    <span className={`fw-bold tracking-wider ${ordenData.progreso >= 0 ? 'text-white' : 'text-muted'}`} style={{ fontSize: '0.75rem' }}>RECEPCIÓN</span>
                                                </div>

                                                {/* Paso 2 */}
                                                <div className="text-center d-flex flex-column align-items-center" style={{ width: '80px' }}>
                                                    <div className={`rounded-circle d-flex align-items-center justify-content-center mb-3 border border-4 transition-all ${getTimelineClass('EN_PROCESO', ordenData.progreso)}`} style={{ width: '60px', height: '60px', backgroundColor: '#1e1e2d' }}>
                                                        <i className="bi bi-tools fs-3"></i>
                                                    </div>
                                                    <span className={`fw-bold tracking-wider ${ordenData.progreso >= 50 ? 'text-white' : 'text-muted'}`} style={{ fontSize: '0.75rem' }}>EN TALLER</span>
                                                </div>

                                                {/* Paso 3 */}
                                                <div className="text-center d-flex flex-column align-items-center" style={{ width: '80px' }}>
                                                    <div className={`rounded-circle d-flex align-items-center justify-content-center mb-3 border border-4 transition-all ${getTimelineClass('FINALIZADO', ordenData.progreso)}`} style={{ width: '60px', height: '60px', backgroundColor: '#1e1e2d' }}>
                                                        <i className="bi bi-check2-all fs-2"></i>
                                                    </div>
                                                    <span className={`fw-bold tracking-wider ${ordenData.progreso >= 100 ? 'text-white' : 'text-muted'}`} style={{ fontSize: '0.75rem' }}>ENTREGA</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="text-center mt-5 p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
                                            <p className="text-white fs-5 mb-0 fw-light">
                                                {ordenData.progreso === 0 && <><i className="bi bi-info-circle text-primary-accent me-2"></i> Su vehículo ha sido ingresado al sistema y aguarda asignación de bahía.</>}
                                                {ordenData.progreso === 50 && <><i className="bi bi-gear-wide-connected text-primary-accent me-2 spin-slow"></i> Nuestros especialistas se encuentran operando su vehículo en este momento.</>}
                                                {ordenData.progreso === 100 && <><i className="bi bi-stars text-success me-2 glow-success"></i> ¡Trabajo finalizado con éxito! Su vehículo está listo para ser retirado.</>}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* Features Section */}
            <section id="servicios" className="py-5 bg-panel-dark border-top border-bottom border-white border-opacity-10">
                <div className="container py-5">
                    <div className="text-center mb-5 pb-3">
                        <span className="text-primary-accent fw-bold tracking-wider text-uppercase small">Nuestra Promesa</span>
                        <h2 className="text-white fw-bolder display-6 mt-2">¿Por qué elegir TallerPro?</h2>
                    </div>
                    <div className="row g-4">
                        {[
                            { icon: 'bi-shield-check', title: 'Calidad Garantizada', desc: 'Repuestos originales y mano de obra certificada con 6 meses de garantía total en todos los servicios.' },
                            { icon: 'bi-speedometer2', title: 'Tiempos Exactos', desc: 'Cumplimos nuestros plazos. Nuestro sistema optimiza cada segundo del trabajo de nuestros mecánicos.' },
                            { icon: 'bi-phone-vibrate', title: 'Transparencia Digital', desc: 'Monitorea el progreso de tu vehículo desde tu celular sin necesidad de realizar llamadas de consulta.' }
                        ].map((feat, i) => (
                            <div className="col-md-4" key={i}>
                                <div className="card h-100 bg-dark bg-opacity-50 border-white border-opacity-10 text-center p-4 hover-lift-lg transition-all rounded-4">
                                    <div className="card-body">
                                        <div className="rounded-circle bg-primary-accent bg-opacity-10 text-primary-accent d-inline-flex align-items-center justify-content-center mb-4 border border-primary-accent border-opacity-25" style={{ width: '80px', height: '80px' }}>
                                            <i className={`bi ${feat.icon} fs-1`}></i>
                                        </div>
                                        <h4 className="text-white fw-bold mb-3">{feat.title}</h4>
                                        <p className="text-muted mb-0">{feat.desc}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Location & Contact Section */}
            <section id="contacto" className="py-5 position-relative overflow-hidden">
                <div className="position-absolute top-0 end-0 w-50 h-100 bg-primary-accent bg-opacity-5" style={{ filter: 'blur(100px)', zIndex: 0 }}></div>
                <div className="container py-5 position-relative z-index-2">
                    <div className="row align-items-center g-5">
                        <div className="col-lg-5 pe-lg-5">
                            <span className="text-primary-accent fw-bold tracking-wider text-uppercase small">Visítanos</span>
                            <h2 className="text-white fw-bolder display-5 mt-2 mb-4">Estamos donde <br />nos necesitas</h2>
                            <p className="text-muted fs-5 mb-5 fw-light">
                                Nuestras instalaciones de primer nivel están diseñadas para brindar el mejor cuidado a tu patrimonio.
                            </p>

                            <div className="d-flex flex-column gap-4">
                                <div className="d-flex align-items-start gap-4 p-3 rounded-4 hover-bg-light transition-all">
                                    <div className="bg-white bg-opacity-10 text-white rounded p-3 d-flex align-items-center justify-content-center shadow-sm">
                                        <i className="bi bi-geo-alt-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 className="text-white fw-bold mb-1">Dirección</h5>
                                        <p className="text-muted mb-0">Av. Principal y 4to Anillo<br />Santa Cruz de la Sierra, Bolivia</p>
                                    </div>
                                </div>

                                <div className="d-flex align-items-start gap-4 p-3 rounded-4 hover-bg-light transition-all">
                                    <div className="bg-white bg-opacity-10 text-white rounded p-3 d-flex align-items-center justify-content-center shadow-sm">
                                        <i className="bi bi-clock-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 className="text-white fw-bold mb-1">Horarios de Atención</h5>
                                        <p className="text-muted mb-0">Lunes a Viernes: 08:00 - 18:00<br />Sábados: 08:00 - 14:00</p>
                                    </div>
                                </div>

                                <div className="d-flex align-items-start gap-4 p-3 rounded-4 hover-bg-light transition-all">
                                    <div className="bg-white bg-opacity-10 text-white rounded p-3 d-flex align-items-center justify-content-center shadow-sm">
                                        <i className="bi bi-telephone-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 className="text-white fw-bold mb-1">Línea Directa</h5>
                                        <p className="text-muted mb-0">+591 69706213<br />senchezzrodrigo@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="col-lg-7">
                            <div className="glass-panel p-2 rounded-4 shadow-glow-lg border-white border-opacity-10 overflow-hidden" style={{ height: '550px' }}>
                                <iframe
                                    width="100%"
                                    height="100%"
                                    frameBorder="0"
                                    scrolling="no"
                                    marginHeight="0"
                                    marginWidth="0"
                                    src="https://maps.google.com/maps?q=-17.75865488805408,-63.1872202183389&hl=es&z=16&output=embed"
                                    title="Ubicación Taller Automotriz Pro"
                                    className="rounded-3"
                                    style={{ filter: 'invert(90%) hue-rotate(200deg) contrast(90%) saturate(80%)' }}
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="mt-auto py-5 border-top border-white border-opacity-10 bg-black">
                <div className="container text-center">
                    <div className="mb-4">
                        <i className="bi bi-heptagon-fill fs-2 text-primary-accent"></i>
                    </div>
                    <h5 className="text-white fw-bold tracking-wider mb-4">Taller<span className="text-primary-accent">Pro</span></h5>
                    <div className="d-flex justify-content-center mb-5">
                        <a href={"https://wa.me/59169706213?text=" + encodeURIComponent("Hola, quisiera saber el estado de mi vehículo.")} target="_blank" rel="noreferrer" className="btn btn-outline-success text-white border-success border-opacity-50 rounded-pill d-inline-flex align-items-center gap-3 px-4 py-3 hover-lift transition-all">
                            <i className="bi bi-whatsapp fs-4"></i>
                            <span className="fw-semibold fs-6">Consulta el estado de tu vehículo</span>
                        </a>
                    </div>
                    <p className="text-muted small mb-0">
                        &copy; {new Date().getFullYear()} Taller Automotriz Pro. Todos los derechos reservados.
                    </p>
                </div>
            </footer>

            <style>{`
                /* Premium Styles & Animations */
                .transition-all { transition: all 0.3s ease; }
                
                .bg-gradient-primary {
                    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-indigo) 100%);
                }
                .bg-gradient-dark-blue {
                    background: linear-gradient(135deg, #0f172a 0%, #1e1e2d 100%);
                }
                .bg-clip-text {
                    -webkit-background-clip: text;
                    background-clip: text;
                }
                .text-transparent { color: transparent !important; }
                .bg-panel-dark { background-color: #12121c; }

                .tracking-wider { letter-spacing: 0.1em; }
                
                .search-input::placeholder { color: rgba(255,255,255,0.3); }
                .search-input:focus { outline: none; background: transparent; color: white; }

                .shadow-glow { box-shadow: 0 0 30px rgba(0, 158, 247, 0.15); }
                .shadow-glow-lg { box-shadow: 0 10px 40px rgba(0, 158, 247, 0.2); }
                .glow-bar { box-shadow: 0 0 15px rgba(0, 158, 247, 0.6); }
                .glow-success { text-shadow: 0 0 10px rgba(80, 205, 137, 0.6); }

                .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
                .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 158, 247, 0.4); }
                
                .hover-lift-lg { transition: transform 0.3s ease; }
                .hover-lift-lg:hover { transform: translateY(-10px); border-color: rgba(0, 158, 247, 0.3) !important; background-color: rgba(30, 30, 45, 0.8) !important; }

                .hover-bg-light:hover { background-color: rgba(255,255,255,0.03); }

                .nav-link-custom { position: relative; padding: 0.5rem 0; opacity: 0.8; transition: opacity 0.2s; }
                .nav-link-custom:hover { opacity: 1; }
                .nav-link-custom::after {
                    content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px;
                    background: var(--accent-primary); transition: width 0.3s ease;
                }
                .nav-link-custom:hover::after { width: 100%; }

                .slide-up-animation { animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
                .fade-in { animation: fadeIn 0.5s ease forwards; }
                .scale-up { animation: scaleUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
                .pulse-glow { animation: pulseGlow 2s infinite; }
                .spin-slow { animation: spin 4s linear infinite; display: inline-block; }

                @keyframes slideUpFade {
                    from { opacity: 0; transform: translateY(40px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                @keyframes scaleUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                @keyframes pulseGlow {
                    0% { box-shadow: 0 0 0 0 rgba(0, 158, 247, 0.4); }
                    70% { box-shadow: 0 0 0 15px rgba(0, 158, 247, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(0, 158, 247, 0); }
                }
                @keyframes spin { 100% { transform: rotate(360deg); } }

                /* Ocultar barra de scroll para este layout puro */
                body::-webkit-scrollbar { width: 8px; }
                body::-webkit-scrollbar-track { background: #0a0a0f; }
                body::-webkit-scrollbar-thumb { background: #1e1e2d; border-radius: 4px; }
                body::-webkit-scrollbar-thumb:hover { background: #009ef7; }
            `}</style>
        </div>
    );
}

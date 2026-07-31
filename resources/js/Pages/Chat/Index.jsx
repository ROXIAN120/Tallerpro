import React, { useState, useEffect } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';
import axios from 'axios';

export default function ChatIndex({ initialConversations, contactos: initialContactos = [] }) {
    const [contactos, setContactos] = useState(initialContactos);
    const [conversations, setConversations] = useState(initialConversations || []);
    const [selectedChat, setSelectedChat] = useState(null);
    const [message, setMessage] = useState('');
    
    // Estados para Campañas
    const [campaignMessage, setCampaignMessage] = useState('');
    const [campaignProgress, setCampaignProgress] = useState({ sent: 0, total: 0, active: false });
    const [campaignTags, setCampaignTags] = useState([]); // Array de etiquetas seleccionadas
    
    // Estados para Añadir Etiqueta Inline
    const [addingTagTo, setAddingTagTo] = useState(null);
    const [newTagValue, setNewTagValue] = useState('');
    
    // Todas las etiquetas únicas disponibles
    const allTags = [...new Set(contactos.flatMap(c => c.etiquetas || []))];
    
    // Cálculo de audiencia objetivo
    const targetAudienceCount = campaignTags.length === 0 
        ? contactos.length 
        : contactos.filter(c => (c.etiquetas || []).some(t => campaignTags.includes(t))).length;

    // Estados del CRM
    const [activeTab, setActiveTab] = useState('bandeja'); // bandeja, contactos, promociones, config
    const [connectionStatus, setConnectionStatus] = useState('loading'); // loading, connected, disconnected
    const [qrCode, setQrCode] = useState(null);

    // Colores del tema (basado en la estética de TallerPro)
    const theme = {
        sidebarBg: '#1e293b',
        sidebarHover: '#334155',
        accent: '#06b6d4',
        textLight: '#f8fafc',
        textMuted: '#94a3b8',
        chatBg: '#0f172a'
    };

    useEffect(() => {
        checkConnection();
        const interval = setInterval(checkConnection, 5000); // Polling cada 5s
        return () => clearInterval(interval);
    }, []);

    const checkConnection = async () => {
        try {
            const res = await axios.get('/chat/status');
            const state = res.data?.instance?.state;
            
            if (state === 'open') {
                setConnectionStatus('connected');
                setQrCode(null);
            } else {
                setConnectionStatus('disconnected');
                fetchQrCode();
            }
        } catch (error) {
            console.error("Error checking status", error);
            setConnectionStatus('disconnected');
        }
    };

    const fetchQrCode = async () => {
        try {
            const res = await axios.get('/chat/qr');
            if (res.data?.base64) {
                setQrCode(res.data.base64);
            }
        } catch (error) {
            console.error("Error fetching QR", error);
        }
    };

    const handleLogout = async () => {
        if(confirm("¿Estás seguro de desconectar tu WhatsApp?")) {
            setConnectionStatus('loading');
            await axios.post('/chat/logout');
            checkConnection();
        }
    };

    const handleSendMessage = async (e) => {
        e.preventDefault();
        if (!message.trim() || !selectedChat) return;

        const tempMessage = message;
        setMessage('');

        // Actualización optimista de la UI
        const newMessage = {
            id: Date.now(),
            body: tempMessage,
            direction: 'outbound',
            created_at: new Date().toISOString()
        };

        const updatedChat = {
            ...selectedChat,
            messages: [...(selectedChat.messages || []), newMessage]
        };
        setSelectedChat(updatedChat);

        // Actualizar la lista lateral para que se vea el último mensaje
        setConversations(conversations.map(c => 
            c.id === selectedChat.id ? updatedChat : c
        ));

        try {
            await axios.post('/chat/send', {
                conversation_id: selectedChat.id,
                message: tempMessage
            });
        } catch (error) {
            console.error("Error al enviar mensaje", error);
            alert("Error al enviar el mensaje. Revisa tu conexión.");
        }
    };

    const handleStartChat = async (cliente_id) => {
        try {
            const res = await axios.post('/chat/start', { cliente_id });
            const chat = res.data.conversation;
            
            setConversations(prev => {
                if (!prev.find(c => c.id === chat.id)) {
                    return [chat, ...prev];
                }
                return prev;
            });
            
            setSelectedChat(chat);
            setActiveTab('bandeja');
        } catch (error) {
            console.error("Error al iniciar chat", error);
            alert("No se pudo iniciar el chat. Verifica el número de teléfono.");
        }
    };

    const confirmAddTag = async (cliente_id) => {
        if (!newTagValue || !newTagValue.trim()) {
            setAddingTagTo(null);
            return;
        }
        
        const tag = newTagValue.trim();
        const contacto = contactos.find(c => c.id === cliente_id);
        const currentTags = contacto.etiquetas || [];
        
        setAddingTagTo(null);
        setNewTagValue('');
        
        if (currentTags.includes(tag)) return; // Evitar duplicados
        
        const newTags = [...currentTags, tag];
        
        // Optimistic update
        setContactos(contactos.map(c => c.id === cliente_id ? { ...c, etiquetas: newTags } : c));
        
        try {
            await axios.post('/chat/etiquetas', { cliente_id, etiquetas: newTags });
        } catch (error) {
            console.error("Error al guardar etiqueta", error);
            alert("Error al guardar la etiqueta.");
            setContactos(contactos.map(c => c.id === cliente_id ? { ...c, etiquetas: currentTags } : c));
        }
    };
    
    const handleRemoveEtiqueta = async (cliente_id, tagToRemove) => {
        const contacto = contactos.find(c => c.id === cliente_id);
        const currentTags = contacto.etiquetas || [];
        const newTags = currentTags.filter(t => t !== tagToRemove);
        
        // Optimistic update
        setContactos(contactos.map(c => c.id === cliente_id ? { ...c, etiquetas: newTags } : c));
        
        try {
            await axios.post('/chat/etiquetas', { cliente_id, etiquetas: newTags });
        } catch (error) {
            console.error("Error al remover etiqueta", error);
            setContactos(contactos.map(c => c.id === cliente_id ? { ...c, etiquetas: currentTags } : c));
        }
    };

    const handleSendCampaign = async (e) => {
        e.preventDefault();
        if (!campaignMessage.trim()) return;
        
        const targetClients = campaignTags.length === 0 
            ? contactos 
            : contactos.filter(c => (c.etiquetas || []).some(t => campaignTags.includes(t)));
            
        if (targetClients.length === 0) return;
        if (!confirm(`¿Estás seguro de enviar este mensaje a ${targetClients.length} clientes progresivamente?`)) return;

        setCampaignProgress({ sent: 0, total: targetClients.length, active: true });
        let sentCount = 0;
        
        for (let i = 0; i < targetClients.length; i++) {
            const cliente = targetClients[i];
            if (!cliente.telefono) continue;
            
            const mensajePersonalizado = campaignMessage.replace(/{nombre}/g, cliente.nombre);
            
            try {
                await axios.post('/chat/campaign', { 
                    message: mensajePersonalizado,
                    telefono: cliente.telefono
                });
                sentCount++;
                setCampaignProgress({ sent: sentCount, total: targetClients.length, active: true });
            } catch (error) {
                console.error("Error al enviar campaña a " + cliente.telefono, error);
            }
            
            // Pausa aleatoria anti-baneo (entre 3 y 6 segundos) si no es el último
            if (i < targetClients.length - 1) {
                const waitTime = Math.floor(Math.random() * (6000 - 3000 + 1)) + 3000;
                await new Promise(r => setTimeout(r, waitTime));
            }
        }
        
        alert(`Campaña terminada. Enviada a ${sentCount} de ${targetClients.length} clientes.`);
        setCampaignMessage('');
        setCampaignProgress({ sent: 0, total: 0, active: false });
    };

    // VISTA DE CONEXIÓN QR
    if (connectionStatus === 'loading' || connectionStatus === 'disconnected') {
        return (
            <AdminLayout>
                <Head title="Conectar WhatsApp" />
                <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '80vh', backgroundColor: theme.chatBg }}>
                    <div className="card shadow-lg border-0" style={{ maxWidth: '500px', width: '100%', borderRadius: '15px', backgroundColor: theme.sidebarBg, color: theme.textLight }}>
                        <div className="card-body text-center p-5">
                            <i className="bi bi-whatsapp mb-3" style={{ fontSize: '3rem', color: theme.accent }}></i>
                            <h3 className="fw-bold mb-2">Conecta tu WhatsApp</h3>
                            <p style={{ color: theme.textMuted }}>Escanea el código QR para acceder al Mini-CRM de TallerPro.</p>
                            
                            {connectionStatus === 'loading' && !qrCode ? (
                                <div className="spinner-border text-info my-4" role="status"><span className="visually-hidden">Cargando...</span></div>
                            ) : (
                                <div className="bg-white p-3 rounded-3 mx-auto mt-4 mb-3 d-inline-block shadow-sm">
                                    {qrCode ? (
                                        <img src={qrCode} alt="WhatsApp QR Code" style={{ width: '250px', height: '250px' }} />
                                    ) : (
                                        <div style={{ width: '250px', height: '250px', display:'flex', alignItems:'center', justifyContent:'center', color:'#ccc' }}>Generando QR...</div>
                                    )}
                                </div>
                            )}
                            <p className="small mb-0" style={{ color: theme.textMuted }}>
                                <i className="bi bi-phone me-1"></i> Abre WhatsApp en tu teléfono &gt; Dispositivos vinculados
                            </p>
                        </div>
                    </div>
                </div>
            </AdminLayout>
        );
    }

    // VISTA DEL CRM CONECTADO
    return (
        <AdminLayout>
            <Head title="WhatsApp CRM" />
            
            <datalist id="all-tags-list">
                {allTags.map(tag => <option key={tag} value={tag} />)}
            </datalist>

            <div className="container-fluid py-4 h-100">
                <div className="row g-0 h-100 rounded-4 overflow-hidden shadow-lg" style={{ minHeight: '80vh', border: `1px solid ${theme.sidebarHover}` }}>
                    
                    {/* MENÚ LATERAL INTERNO DEL CRM */}
                    <div className="col-auto d-flex flex-column align-items-center py-3" style={{ width: '80px', backgroundColor: theme.sidebarBg, borderRight: `1px solid ${theme.sidebarHover}` }}>
                        <button onClick={() => setActiveTab('bandeja')} className="btn mb-4 position-relative" style={{ color: activeTab === 'bandeja' ? theme.accent : theme.textMuted }}>
                            <i className="bi bi-chat-dots-fill fs-4"></i>
                        </button>
                        <button onClick={() => setActiveTab('contactos')} className="btn mb-4" title="Contactos" style={{ color: activeTab === 'contactos' ? theme.accent : theme.textMuted }}>
                            <i className="bi bi-people-fill fs-4"></i>
                        </button>
                        <button onClick={() => setActiveTab('promociones')} className="btn mb-4" title="Promociones Masivas" style={{ color: activeTab === 'promociones' ? theme.accent : theme.textMuted }}>
                            <i className="bi bi-megaphone-fill fs-4"></i>
                        </button>
                        
                        <div className="mt-auto">
                            <button onClick={() => setActiveTab('config')} className="btn" title="Ajustes" style={{ color: activeTab === 'config' ? theme.accent : theme.textMuted }}>
                                <i className="bi bi-gear-fill fs-4"></i>
                            </button>
                        </div>
                    </div>

                    {/* LISTA (BANDEJA/CONTACTOS/ETC) */}
                    <div className="col-12 col-md-4 col-lg-3 d-flex flex-column" style={{ backgroundColor: theme.chatBg, borderRight: `1px solid ${theme.sidebarHover}` }}>
                        <div className="p-4 border-bottom" style={{ borderColor: theme.sidebarHover }}>
                            <h5 className="mb-0 fw-bold" style={{ color: theme.textLight }}>
                                {activeTab === 'bandeja' && 'Bandeja de Entrada'}
                                {activeTab === 'contactos' && 'Directorio Web'}
                                {activeTab === 'promociones' && 'Campañas'}
                                {activeTab === 'config' && 'Configuración'}
                            </h5>
                        </div>
                        
                        {activeTab === 'bandeja' && (
                            <div className="list-group list-group-flush flex-grow-1" style={{ overflowY: 'auto' }}>
                                {conversations.length === 0 ? (
                                    <div className="text-center p-4" style={{ color: theme.textMuted }}>No hay mensajes recientes.</div>
                                ) : (
                                    conversations.map(chat => (
                                        <button 
                                            key={chat.id} 
                                            onClick={() => setSelectedChat(chat)}
                                            className="list-group-item list-group-item-action p-3 border-0 border-bottom"
                                            style={{ 
                                                backgroundColor: selectedChat?.id === chat.id ? theme.sidebarHover : 'transparent',
                                                borderColor: `${theme.sidebarHover} !important`,
                                                color: theme.textLight
                                            }}
                                        >
                                            <div className="d-flex w-100 justify-content-between">
                                                <h6 className="mb-1 fw-bold">{chat.contact_name || chat.phone_number}</h6>
                                                <small style={{ color: theme.accent }}>
                                                    {chat.last_message_at ? new Date(chat.last_message_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}
                                                </small>
                                            </div>
                                            <p className="mb-1 text-truncate" style={{ fontSize: '0.9rem', color: theme.textMuted }}>
                                                {chat.messages && chat.messages.length > 0 ? chat.messages[0].body : '...'}
                                            </p>
                                        </button>
                                    ))
                                )}
                            </div>
                        )}

                        {activeTab === 'contactos' && (
                            <div className="list-group list-group-flush flex-grow-1" style={{ overflowY: 'auto' }}>
                                {contactos.length === 0 ? (
                                    <div className="text-center p-4" style={{ color: theme.textMuted }}>No hay contactos guardados.</div>
                                ) : (
                                    contactos.map(contacto => (
                                        <div 
                                            key={contacto.id} 
                                            className="list-group-item p-3 border-0 border-bottom d-flex align-items-center"
                                            style={{ 
                                                borderColor: `${theme.sidebarHover} !important`,
                                                backgroundColor: 'transparent',
                                                color: theme.textLight
                                            }}
                                        >
                                            <div className="rounded-circle d-flex justify-content-center align-items-center me-3" style={{ width: '40px', height: '40px', backgroundColor: theme.accent, color: 'white' }}>
                                                <i className="bi bi-person-fill fs-5"></i>
                                            </div>
                                            <div className="flex-grow-1">
                                                <h6 className="mb-0 fw-bold">{contacto.nombre}</h6>
                                                <small style={{ color: theme.textMuted }}><i className="bi bi-telephone-fill me-1" style={{fontSize: '0.7rem'}}></i>{contacto.telefono}</small>
                                                <div className="mt-1">
                                                    {(contacto.etiquetas || []).map((tag, idx) => (
                                                        <span key={idx} className="badge bg-secondary me-1 text-light position-relative" style={{ fontSize: '0.65rem' }}>
                                                            {tag}
                                                            <i className="bi bi-x-circle-fill ms-1" style={{cursor: 'pointer'}} onClick={(e) => { e.stopPropagation(); handleRemoveEtiqueta(contacto.id, tag); }}></i>
                                                        </span>
                                                    ))}
                                                    {addingTagTo === contacto.id ? (
                                                        <span className="d-inline-flex align-items-center">
                                                            <input 
                                                                type="text" 
                                                                className="form-control form-control-sm bg-dark text-white border-secondary me-1 p-0 px-1 shadow-none" 
                                                                style={{ width: '100px', fontSize: '0.65rem', height: '22px' }}
                                                                list="all-tags-list"
                                                                autoFocus
                                                                value={newTagValue}
                                                                onChange={e => setNewTagValue(e.target.value)}
                                                                onKeyDown={e => {
                                                                    if (e.key === 'Enter') confirmAddTag(contacto.id);
                                                                    if (e.key === 'Escape') { setAddingTagTo(null); setNewTagValue(''); }
                                                                }}
                                                            />
                                                            <i className="bi bi-check-circle text-success fs-6" style={{cursor: 'pointer'}} onClick={() => confirmAddTag(contacto.id)}></i>
                                                        </span>
                                                    ) : (
                                                        <span className="badge border border-secondary text-muted hover-scale" style={{ cursor: 'pointer', fontSize: '0.65rem' }} onClick={() => {setAddingTagTo(contacto.id); setNewTagValue('');}}>
                                                            + Añadir
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                            <button className="btn btn-sm btn-outline-info rounded-circle ms-2" title="Iniciar Chat" onClick={() => handleStartChat(contacto.id)}>
                                                <i className="bi bi-chat-dots"></i>
                                            </button>
                                        </div>
                                    ))
                                )}
                            </div>
                        )}

                        {activeTab === 'config' && (
                            <div className="p-4">
                                <div className="card bg-dark text-white border-0 mb-3" style={{ backgroundColor: theme.sidebarHover }}>
                                    <div className="card-body text-center">
                                        <i className="bi bi-phone text-success fs-1 mb-2"></i>
                                        <h6>Dispositivo Conectado</h6>
                                        <p className="small text-muted mb-0">Instancia: TallerPro</p>
                                    </div>
                                </div>
                                <button onClick={handleLogout} className="btn btn-outline-danger w-100 rounded-pill">
                                    <i className="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </button>
                            </div>
                        )}

                        {activeTab === 'promociones' && (
                            <div className="p-4" style={{ color: theme.textLight }}>
                                <h6 className="fw-bold mb-3"><i className="bi bi-megaphone me-2" style={{color: theme.accent}}></i>Nueva Campaña Masiva</h6>
                                <p className="small text-muted mb-4">Envía un mensaje promocional o informativo a <strong>TODOS</strong> tus clientes registrados ({contactos.length} clientes en total).</p>
                                
                                <div className="card bg-dark border-0 shadow-sm" style={{ backgroundColor: theme.sidebarHover }}>
                                    <div className="card-body p-4">
                                        <form onSubmit={handleSendCampaign}>
                                            <div className="mb-3">
                                                <label className="form-label text-muted small fw-semibold">Público Objetivo (Filtro por Etiquetas)</label>
                                                <div className="d-flex flex-wrap gap-2 mb-2">
                                                    <div 
                                                        className={`badge p-2 ${campaignTags.length === 0 ? 'bg-info text-dark' : 'bg-dark border border-secondary text-muted'}`}
                                                        style={{ cursor: 'pointer' }}
                                                        onClick={() => setCampaignTags([])}
                                                    >
                                                        Todos los clientes
                                                    </div>
                                                    {campaignTags.map(tag => (
                                                        <div 
                                                            key={tag}
                                                            className="badge p-2 bg-info text-dark"
                                                            style={{ cursor: 'pointer' }}
                                                            onClick={() => setCampaignTags(campaignTags.filter(t => t !== tag))}
                                                        >
                                                            {tag} <i className="bi bi-x-circle ms-1"></i>
                                                        </div>
                                                    ))}
                                                    
                                                    <div className="d-flex align-items-center">
                                                        <select 
                                                            className="form-select form-select-sm bg-dark text-white border-secondary shadow-none"
                                                            style={{ width: 'auto' }}
                                                            value=""
                                                            onChange={(e) => {
                                                                if (e.target.value && !campaignTags.includes(e.target.value)) {
                                                                    setCampaignTags([...campaignTags, e.target.value]);
                                                                }
                                                            }}
                                                        >
                                                            <option value="" disabled>+ Agregar Filtro</option>
                                                            {allTags.filter(t => !campaignTags.includes(t)).map(tag => (
                                                                <option key={tag} value={tag}>{tag}</option>
                                                            ))}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className="small text-info mb-3">
                                                    Se enviará a: <strong>{targetAudienceCount} clientes</strong>
                                                </div>
                                            </div>

                                            <div className="mb-3">
                                                <label className="form-label text-muted small fw-semibold">Mensaje de la Campaña</label>
                                                <textarea 
                                                    className="form-control bg-dark text-white border-secondary" 
                                                    rows="5"
                                                    placeholder="Escribe el mensaje aquí... Tip: Puedes usar {nombre} para personalizar el mensaje."
                                                    value={campaignMessage}
                                                    onChange={e => setCampaignMessage(e.target.value)}
                                                    required
                                                ></textarea>
                                                <div className="form-text text-muted small mt-2">
                                                    Ejemplo: ¡Hola &#123;nombre&#125;! Tenemos 20% de descuento en cambios de aceite esta semana.
                                                </div>
                                            </div>
                                            <button 
                                                type="submit" 
                                                className="btn w-100 rounded-pill text-white fw-bold d-flex flex-column justify-content-center align-items-center" 
                                                style={{ backgroundColor: theme.accent, borderColor: theme.accent }}
                                                disabled={campaignProgress.active || targetAudienceCount === 0}
                                            >
                                                {campaignProgress.active ? (
                                                    <div className="w-100 px-3 py-1">
                                                        <div className="d-flex align-items-center justify-content-center mb-1">
                                                            <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> 
                                                            Enviando... ({campaignProgress.sent} de {campaignProgress.total})
                                                        </div>
                                                        <div className="progress w-100 bg-dark" style={{ height: '5px' }}>
                                                            <div className="progress-bar bg-white" style={{ width: `${(campaignProgress.sent / campaignProgress.total) * 100}%`, transition: 'width 0.3s ease' }}></div>
                                                        </div>
                                                    </div>
                                                ) : (
                                                    <div className="d-flex align-items-center py-1"><i className="bi bi-send-fill me-2"></i> Enviar a {targetAudienceCount} Clientes</div>
                                                )}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* ÁREA PRINCIPAL (CHAT O PROMOCIÓN) */}
                    <div className="col d-flex flex-column bg-white position-relative" style={{ backgroundColor: '#f8fafc' }}>
                        {selectedChat && activeTab === 'bandeja' ? (
                            <>
                                {/* Cabecera del Chat */}
                                <div className="p-3 border-bottom d-flex align-items-center bg-white shadow-sm" style={{ zIndex: 10 }}>
                                    <div className="rounded-circle d-flex justify-content-center align-items-center me-3" style={{ width: '45px', height: '45px', backgroundColor: theme.accent, color: 'white' }}>
                                        <i className="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 className="mb-0 fw-bold text-dark">{selectedChat.contact_name || selectedChat.phone_number}</h6>
                                        <small className="text-success"><i className="bi bi-circle-fill me-1" style={{fontSize:'8px'}}></i>En línea</small>
                                    </div>
                                    <div className="ms-auto">
                                        <button className="btn btn-light rounded-circle me-1"><i className="bi bi-telephone"></i></button>
                                        <button className="btn btn-light rounded-circle"><i className="bi bi-three-dots-vertical"></i></button>
                                    </div>
                                </div>

                                {/* Mensajes del Chat */}
                                <div className="flex-grow-1 p-4" style={{ overflowY: 'auto', backgroundColor: '#e2e8f0', backgroundImage: 'url("https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png")', backgroundRepeat: 'repeat', opacity: 0.9 }}>
                                    
                                    {selectedChat.messages && selectedChat.messages.map(msg => (
                                        <div key={msg.id} className={`d-flex mb-3 ${msg.direction === 'inbound' ? 'justify-content-start' : 'justify-content-end'}`}>
                                            <div className={`p-3 rounded-4 shadow-sm ${msg.direction === 'inbound' ? 'bg-white text-dark' : 'text-white'}`} style={{ maxWidth: '75%', backgroundColor: msg.direction === 'inbound' ? 'white' : theme.accent, borderTopLeftRadius: msg.direction === 'inbound' ? '0' : '', borderTopRightRadius: msg.direction === 'outbound' ? '0' : '' }}>
                                                {msg.body}
                                                <div className={`text-end mt-1 ${msg.direction === 'inbound' ? 'text-muted' : 'text-white-50'}`} style={{ fontSize: '0.7rem' }}>
                                                    {new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                                    {msg.direction === 'outbound' && <i className="bi bi-check2-all ms-1"></i>}
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* Caja de texto para enviar */}
                                <div className="p-3 bg-white border-top">
                                    <form onSubmit={handleSendMessage} className="d-flex align-items-center">
                                        <button type="button" className="btn btn-light text-muted me-2 rounded-circle" style={{ width: '45px', height: '45px' }}>
                                            <i className="bi bi-plus-lg fs-5"></i>
                                        </button>
                                        <input 
                                            type="text" 
                                            className="form-control rounded-pill px-4 py-3 bg-light border-0" 
                                            placeholder="Escribe un mensaje..." 
                                            value={message}
                                            onChange={(e) => setMessage(e.target.value)}
                                            style={{ boxShadow: 'none' }}
                                        />
                                        <button type="submit" className="btn rounded-circle ms-2 text-white" style={{ width: '50px', height: '50px', backgroundColor: theme.accent, border: 'none' }}>
                                            <i className="bi bi-send-fill fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </>
                        ) : (
                            <div className="h-100 d-flex flex-column justify-content-center align-items-center text-muted" style={{ backgroundColor: theme.chatBg }}>
                                <i className="bi bi-chat-square-quote mb-4" style={{ fontSize: '5rem', opacity: 0.1, color: theme.accent }}></i>
                                <h4 className="fw-bold text-white">CRM WhatsApp TallerPro</h4>
                                <p style={{ color: theme.textMuted }}>Selecciona una opción del menú lateral para comenzar.</p>
                                <span className="badge rounded-pill bg-dark border border-secondary px-3 py-2 mt-2">
                                    <i className="bi bi-lock-fill me-1"></i> Cifrado de extremo a extremo
                                </span>
                            </div>
                        )}
                    </div>

                </div>
            </div>
        </AdminLayout>
    );
}

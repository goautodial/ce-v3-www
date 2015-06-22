<?php
############################################################################################
####  Name:             userauth_lang.php                                               ####
####  Type:             language file                                                   ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Ltd. - J.Samantra, J.Dionisio, W.Briones             ####
####  License:          AGPLv2                                                          ####
############################################################################################

// views/usergroups - login
$lang['ua_login']               = 'Iniciar Sesión';
$lang['ua_logout']              = 'Cerrar sesión';
$lang['ua_remember_me']         = 'acuérdate de mí';
$lang['ua_user']                = 'Usario';
$lang['ua_administrator_login'] = 'AMINISTRADOR DE INICIO DE SESION';
$lang['ua_banner_text']         = 'Capacitar a los Contact Centers de Próxima Generación';
$lang['ua_empowering']          = 'Empoderar';
$lang['ua_thenextgen']          = 'La nueva generación';
$lang['ua_contactcenters']      = 'Centros de Contacto';

$lang['ua_welcome']           =       'Bienvenida!';
$lang['ua_getstarted']        =       'Empezando!';
$lang['ua_needhelp']          =       'Necesitas ayuda?';
$lang['ua_welcomephrase']     =       'GoAutoDial es un sistema de centro de llamadas de código abierto a nivel empresarial. Escalable a cientos de asientos y puede utilizar VoIP, ISDN o troncales analógicas. Visítenos @ ';
$lang['ua_getstartedphrase']  =       'El GoAutoDial Guía de introducción le ayudará a poner en marcha su experiencia GoAutoDial. Incluye paso a paso la instalación, configuración troncal SIP, lleva la carga, de conexión del agente y tomar su primera llamada.';
$lang['ua_needhelpphrase']    =       'Estamos aquí para usted. Ofrecemos un servicio de primera clase a precios asequibles. Y elegir nuestro servicio ayudará a apoyar el desarrollo del proyecto.';
$lang['ua_licence']           =       'GoAutoDial viene sin garantías o garantías de ningún tipo, ya sea escrita o implícita. La distribución es liberado como GPL. Los paquetes individuales en la distribución vienen con sus propias licencias.';

$lang['ua_AgentLogin']   =     'Agente de inicio de sesión';
$lang['ua_AdminLogin']   =     'Administrador de inicio de sesión';
$lang['ua_VTigerCRM']   =     'VTigerCRM';
$lang['ua_Community']   =     'Comunidad';
$lang['ua_VOIPStore']   =     'VOIP tienda';

$lang['phpMyAdmin']   =     'phpMyAdmin';
$lang['SupportCenter']   =     'Centro de soporte';
$lang['vicidialadmin']   =     'Vicidial administración';
$lang['goadmin']   =     'GO administración';

// views/usergroups - login userauth userForm
$lang['ua_password']            = 'Contraseña';

// controllers/admin - usergroups
// views/usergroups - login userauth userForm
$lang['ua_username']            = 'Nombre de usuario';

// controllers/admin - usergroups
$lang['ua_group_added']         = 'Grupo agregado con éxito';
$lang['ua_group_added_err']     = 'Algo salió mal. El grupo no se agregó.';
$lang['ua_group_edited']        = 'Grupo editado con éxito';
$lang['ua_group_edited_err']    = 'Algo salió mal. El grupo no fue editado.';
$lang['ua_group_removed']       = 'Grupo Suprimido correctamente.';
$lang['ua_missing']             = 'Algo faltaba. Fue un campo deja en blanco?';
$lang['ua_removal_err']         = 'Se produjo un error. No se completó su retirada.';
$lang['ua_removal_err_members'] = 'El Grupo no puede ser eliminado, ya que hay actualmente miembros en ella. Por favor reasignar todos los miembros del grupo y vuelve a intentarlo.';
$lang['ua_status']              = 'Estado';
$lang['ua_user_added']          = 'Usuario añadido correctamente';
$lang['ua_user_edited']         = 'Usuario editado con éxito';
$lang['ua_user_exists']         = 'El usuario ya existe';
$lang['ua_user_not_removed']    = 'No se pudo eliminar ese usuario';
$lang['ua_user_removed']        = 'Usuario eliminado con éxito!';

// controllers/admin - usergroups
// views/usergroups - groupForm
$lang['ua_groupdescription']    = 'Descripción del Grupo';
$lang['ua_editgroup']           = 'Editar grupo';
$lang['ua_groupname']           = 'Nombre del grupo';

// controllers/admin - usergroups
// views/usergroups - userForm
$lang['ua_edituser']            = 'Editar usuario';
$lang['ua_passconf']            = 'Confirmación de la contraseña';

// controllers/admin - usergroups
// views/usergroups - userauth
$lang['ua_addgroup']            = 'Agregar Grupo';
$lang['ua_manage_title']        = 'Administración Gestión de usuarios';

// controllers/admin - usergroups
// views/usergroups - userauth userForm
$lang['ua_adduser']             = 'Agregar usuario';
$lang['ua_email']               = 'Dirección De Correo Electrónico';
$lang['ua_fullname']            = 'Nombre Completo';
$lang['ua_group']               = 'Grupo';

// views/usergroups - userauth
$lang['ua_description']         = 'Descripción';
$lang['ua_edit']                = 'editar';
$lang['ua_enabled']             = 'habilitado';
$lang['ua_manage_user']         = 'Gestión de usuarios';
$lang['ua_manage_group']        = 'Dirección del Grupo';
$lang['ua_no']                  = 'No';
$lang['ua_remove']              = 'Quitar';
$lang['ua_yes']                 = 'Sí';

// views/usergroups - userForm
$lang['ua_leave_blank']         = 'dejar en blanco para ningún cambio';

//views/usergroups - groupForm
$lang['ua_255_char_max']        = '255 caracteres como máximo';

//views/usergroups - groupForm userForm
$lang['ua_form_mode_error']     = 'Forma: Modo de error';

// controllers - user
$lang['ua_auth_denied']         = "Usted no está autorizado a ver esta página.";
$lang['ua_auth_err_title']      = 'Autentificación de error';
$lang['ua_auth_not_logged']     = "No pareces estar conectado. Usted puede registrarse aquí.";
$lang['ua_log_error']           = 'El nombre de usuario y / o la contraseña introducida es incorrecta.';
$lang['ua_login_error']         = 'Entrar Error';
$lang['ua_logout_title']        = 'Cerrar sesión';
$lang['ua_logout_txt']          = 'Se le ha cerrado la sesión. Puede volver a iniciar sesión aquí.';
$lang['ua_name_and_pswd']       = 'Tanto nombre de usuario y la contraseña deben ser rellenados. Por favor introduzca ellos y vuelva a intentarlo.';

// controllers - install

$lang['ua_install']             = 'Instalador';
$lang['ua_install_admin_err_1'] = 'Parece que tu usuario administrador ya está presente.';
$lang['ua_install_admin_err_2'] = 'Si va a volver a instalar el sistema USERAUTH, usted debería ser capaz de eliminar de forma segura el controlador de instalar y utilizar su información existente.';
$lang['ua_install_ok_title']    = 'Instale éxito';
$lang['ua_install_ok_txt_1']    = 'Su sistema de gestión USERAUTH CodeIgniter ya está instalado.';
$lang['ua_install_ok_txt_2']    = 'Ahora puede iniciar sesión como "admin" y "admin". Después de hacer login, puede editar esta información.';
$lang['ua_install_ok_txt_3']    = 'Ahora Le recomendamos encarecidamente que volver atrás y eliminar el controlador de instalar.';

// models - user_group_model
$lang['ua_error_no_suicide']    = 'No se puede eliminar a ti mismo!';


//Users index
$lang['goUsers_users'] = "Usuarios";
$lang['goUsers_searchUsers'] = "Usuarios de búsqueda";
$lang['goUsers_agentId'] = "Identificación del agente";
$lang['goUsers_agentName'] = "Nombre del agente";
$lang['goUsers_level'] = "Nivel";
$lang['goUsers_status'] = "Estado";
$lang['goUsers_action'] = "Acción";
$lang['goUsers_actionEnabledSelected'] = "Habilitar seleccionada";
$lang['goUsers_actionDisabledSelected'] = "Desactivado seleccionada";
$lang['goUsers_actionDeleteSelected'] = "Eliminar seleccionados";
$lang['goUsers_addNewUser'] = "Agregar nuevo usuario";
$lang['goUsers_displaying'] = "Viendo del 1 al 22 de 22 usuarios";

//Users tooltips
$lang['goUsers_tooltip_agentId'] = "Al hacer clic en el ID de agente o el icono Modificar mostrará la siguiente pantalla y permitirá editar las configuraciones del usuario.";
$lang['goUsers_tooltip_modifyUser'] = "Modificar usuario";
$lang['goUsers_tooltip_level'] = "Nivel - Define el permiso concedido a un usuario. <br>Los ajustes actuales son: <br>
Nivel 1-6 - Nivel Agent. Sólo se puede acceder a la conexión del agente . No se puede modificar la configuración de la cuenta. Privilegio Limited. <br>
Nivel 7-8 - Nivel de administración. Se puede acceder tanto de conexión del agente y administrador salpicadero. Puede realizar cambios en Configuración de la cuenta.";
$lang['goUsers_tooltip_actionEdit'] = "Al hacer clic en el ID de agente o el icono Modificar mostrará la siguiente pantalla y permitirá editar las configuraciones del usuario.";
$lang['goUsers_tooltip_actionDelete'] = "Eliminar usuario";
$lang['goUsers_tooltip_actionIcon'] = "Info Icono da toda la información relevante sobre la actividad del agente y el estado . Permite administrador forzar usuario cerrar la sesión.";

$lang['goUsers_tooltip_goToFirstPage'] = "Ir a la primera página";
$lang['goUsers_tooltip_goToPreviousPage'] = "Ir a la página anterior";
$lang['goUsers_tooltip_goToPage'] = "Ir a la página";
$lang['goUsers_tooltip_goToNextPage'] = "Ir a la página siguiente";
$lang['goUsers_tooltip_goToLastPage'] = "Ir a la última página";
$lang['goUsers_tooltip_backToPaginatedView'] = "Volver a Ver paginado";

$lang['goUsers_generatePhoneLogins'] = "Generar teléfono Iniciar sesión(s)";
$lang['goUsers_agentLoginLogoutTime'] = "Agente de inicio de sesión/Hora de desconexión";
$lang['goUsers_outboundCallsForThisTimePeriod'] = "Llamadas salientes para este período de tiempo (límite ficha 25)";
$lang['goUsers_inboundCloserCallsForThisTimePeriod'] = "Llamadas entrantes / Closer para este período (límite ficha 25)";
$lang['goUsers_agentActivityForThisTime'] = "Agente Actividad para este período (25 de carrera)";
$lang['goUsers_recordingForThisTimePeriod'] = "Grabación Para este período de tiempo (25 límite de registro)";
$lang['goUsers_manualOutboundCallsForThisTimePeriod'] = "Llamadas salientes manuales Para este período de tiempo (límite ficha 25)";
$lang['goUsers_leadSearchesForThisTimePeriod'] = "Búsquedas principal de este período de tiempo (límite ficha 25)";

//Add New User
$lang['goUsers_wizard'] = "Asistente de Usuarios";
$lang['goUsers_step1'] = "Paso 1";
$lang['goUsers_wizardType'] = "Tipo Asistente";
$lang['goUsers_addNewUser'] = "Agregar nuevo usuario";
$lang['goUsers_next'] = "Siguiente";
$lang['goUsers_step2'] = "Paso 2";
$lang['goUsers_currentUsers'] = "Los usuarios actuales";
$lang['goUsers_addSeats'] = "Asiento(s) adicional";
$lang['goUsers_cancel'] = "Cancelar";
$lang['goUsers_warning'] = "Advertencia: La creación de usuarios adicionales con extensión telefónica le pondrá por encima del límite de <?=$extenLimit?>. Eso significa que los usuarios recién añadidos ya no tienen extensiones de teléfono añadidos con ellos.";
$lang['goUsers_wizard2'] = "Usuarios Asistente »Añadir Nuevo Usuario";
$lang['goUsers_step3'] = "Paso 3";
$lang['goUsers_userGroup'] = "Grupo de Usuarios";
$lang['goUsers_userId'] = "Identificación del usuario";
$lang['goUsers_password'] = "Contraseña";
$lang['goUsers_fullName'] = "Nombre Completo";
$lang['goUsers_active'] = "Activo";
$lang['goUsers_activeYes'] = "Sí";
$lang['goUsers_activeNo'] = "No";
$lang['goUsers_back'] = "Espalda";
$lang['goUsers_save'] = "Guardar";
$lang['goUsers_success'] = "Éxito : Usuario(s) Nueva creado con éxito";
$lang['goUsers_ok'] = "Okay";

$lang['goUsers_selectDateRange'] = "Seleccione Intervalo de fechas";
$lang['goUsers_phoneExtension'] = "Extensión de teléfono";
$lang['goUsers_agentIsInCampaign'] = "Agente está en campaña";
$lang['goUsers_hangupLastCallAt'] = "Colgar última llamada a";
$lang['goUsers_closerGroups'] = "Grupos más estrechos";
$lang['goUsers_agentTalkTimeAndStatus'] = "Agente de tiempo de conversación y Estado";
$lang['goUsers_count'] = "Contar";
$lang['goUsers_otherStaticsInfo'] = "Otra Información Estática";
$lang['goUsers_agentLoginLogouts'] = "Agente de inicio de sesión/Cerrar sesión";
$lang['goUsers_outboundCallThisTime'] = "Llamadas salientes este momento";
$lang['goUsers_inboundCloserCalls'] = "Entrante/Grupos más estrechos";
$lang['goUsers_agentActivity'] = "Actividad Agente";
$lang['goUsers_recording'] = "Grabación";
$lang['goUsers_manualOutbound'] = "Outbound Manual";
$lang['goUsers_leadSearch'] = "Búsquedas de plomo";
$lang['goUsers_agentLoginLogoutTime'] = "Agente de inicio de sesión/tiempo Cerrar sesión";
$lang['goUsers_event'] = "Evento";
$lang['goUsers_date'] = "Fecha";
$lang['goUsers_time'] = "Tiempo";
$lang['goUsers_campaign'] = "Campaña";
$lang['goUsers_computer'] = "Computadora";
$lang['goUsers_totalCalls'] = "Total de llamadas";
$lang['goUsers_perPage'] = "Por página";
$lang['goUsers_dateTime'] = "Fecha / Hora";
$lang['goUsers_length'] = "Longitud";
$lang['goUsers_phone'] = "Teléfono";
$lang['goUsers_group'] = "Grupo";
$lang['goUsers_list'] = "Lista";
$lang['goUsers_lead'] = "Plomo";
$lang['goUsers_hangupReason'] = "Razón Colgar";
$lang['goUsers_waits'] = "Esperar(s)";
$lang['goUsers_agents'] = "Agentes";
$lang['goUsers_pause'] = "Pausa";
$lang['goUsers_wait'] = "Esperar";
$lang['goUsers_talk'] = "Hablar";
$lang['goUsers_disposition'] = "Disposición";
$lang['goUsers_dead'] = "Muerto";
$lang['goUsers_customer'] = "Cliente";
$lang['goUsers_pauseCode'] = "Código de pausa";
$lang['goUsers_seconds'] = "Segundo";
$lang['goUsers_recid'] = "Recid";
$lang['goUsers_filename'] = "Nombre del archivo";
$lang['goUsers_location'] = "Ubicación";
$lang['goUsers_callType'] = "Tipo de llamada";
$lang['goUsers_server'] = "Servidor";
$lang['goUsers_callerId'] = "Identificación de llamadas";
$lang['goUsers_type'] = "Tipo";
$lang['goUsers_results'] = "Resultados";
$lang['goUsers_query'] = "Pregunta";
$lang['goUsers_leadInformation'] = "Información sobre el Plomo";
$lang['goUsers_leadId'] = "Identificación del plomo";
$lang['goUsers_listId'] = "Identificación del Listado";
$lang['goUsers_address'] = "Dirección";
$lang['goUsers_phoneCode'] = "Código del teléfono";
$lang['goUsers_phoneNumber'] = "Número de teléfono";
$lang['goUsers_city'] = "Ciudad";
$lang['goUsers_state'] = "Estado";
$lang['goUsers_postalCode'] = "Código Postal";
$lang['goUsers_comment'] = "Comentario";
$lang['goUsers_forceLogout'] = "Fuerza Cerrar sesión";
$lang['goUsers_close'] = "Cerca";
$lang['goUsers_fullName'] = "Nombre Completo";
$lang['goUsers_additionalSeats'] = "Asientos adicionales";

//Modify User
$lang['goUsers_modify'] = "Modificar Usuario";
$lang['goUsers_phoneLogin'] = "Teléfono entrada";
$lang['goUsers_phonePassword'] = "Contraseña del teléfono";
$lang['goUsers_hotkeys'] = "Teclas de acceso rápido";
$lang['goUsers_update'] = "Actualización";
$lang['goUsers_updateSuccessful'] = "Actualización exitosa";
$lang['goUsers_modifySameUserLevel'] = "Modificar Nivel de Usuario Mismo";
$lang['goUsers_userLevel'] = "Nivel de Usuario";

// Delete popup form
$lang['goUsers_deleteQuestion'] = "Realmente desea eliminar este agente ?";
$lang['goUsers_successDelete'] = "Éxito : Usuario(s) eliminada";
$lang['goUsers_successDeleteBatch'] = "Éxito: acción completa por lotes";

//USERSTATUS.PHP
$lang['goUsers_userStatus'] = "Condición de Usuario";
$lang['goUsers_agentLoggedInAtServer'] = "Agente conectado al servidor";
$lang['goUsers_inSession'] = "En la sesión";
$lang['goUsers_fromPhone'] = "Del teléfono";
$lang['goUsers_agentIsInCampaign'] = "Agente está en campaña";
$lang['goUsers_hangupLastCallAt'] = "Colgar última llamada a";
$lang['goUsers_closerGroups'] = "Grupos más estrechos";
$lang['goUsers_emergencyLogout'] = "Salir de Emergencia";
$lang['goUsers_selectDateRange'] = "Seleccione rango de fechas";
$lang['goUsers_agentTalkTimeAndStatus'] = "Agente de tiempo de conversación y Estado";
$lang['goUsers_outboundCallsForThisTimePeriod1k'] = "Llamadas salientes para este período de tiempo (límite de registro 1000)";
$lang['goUsers_inboundCloserCallsForThisTimePeriod1k'] = "Llamadas entrantes / Closer para este período (límite de registro 1000)";
$lang['goUsers_agentActivityForThisTimePeriod1k'] = "Agente Actividad para este período (1000 límite)";
$lang['goUsers_recordingForThisTimePeriod'] = "Grabación Para este período de tiempo (límite de registro 1000)";
$lang['goUsers_manualOutboundCallsForThisTimePeriod'] = "Llamadas salientes manuales Para este período de tiempo (límite de registro 1000)";
$lang['goUsers_dialed'] = "Marcadas";
$lang['goUsers_callerId'] = "Identificador de llamadas";
$lang['goUsers_alias'] = "Alias";
$lang['goUsers_preset'] = "Preajuste";
$lang['goUsers_c3hu'] = "C3HU";
$lang['goUsers_leadSearchesForThisTimePeriod1k'] = "Búsquedas principal de este período de tiempo (límite de registro 1000)";

$lang['goUsers_errorSessionExpiredPleaseRelogin'] = "Error: sesión ha finalizado por favor, vuelva a iniciar sesión";
$lang['goUsers_errorOneOrMoreOfThePhoneLogin'] = "Error: Uno o más de los teléfonos de usuario ya ha especificado existe.";
$lang['goUsers_errorEmptyData'] = "Error: datos vacío";
$lang['goUsers_errorEmptyServerInformation'] = "Error: la información del servidor vacío";
$lang['goUsers_errorSomethingWentWrongToYourData'] = "Error: Algo salió mal a sus datos";
$lang['goUsers_errorSomethingWentWrongEitherOnSaving'] = "Error: Algo salió mal, ya sea en verano o en asterisco recarga";
$lang['goUsers_errorSomethingWentWrongInSavingData'] = "Error: Algo salió mal en el ahorro de los datos";
$lang['goUsers_errorEmptyRawDataKindlyCheckYourData'] = "Error: datos brutos vacíos amablemente comprobar sus datos";
$lang['goUsers_thereIsNoDataToBeProcess'] = "No hay datos para ser proceso";
$lang['goUsers_cantReloadAsteriskSorry'] = "No se puede recargar asterisco lo siento";
$lang['goUsers_ohNoSomethingWentWrongOnReloadingAsterisk'] = "¡Oh, no algo salió mal en la recarga asterisco";
$lang['goUsers_errorYouAreNotAllowedToModifyUser'] = "Error: No se le permite modificar usuario";
$lang['goUsers_errorYouAreNotAllowedToDeleteUser'] = "Error: No tienes permiso para borrar este usuario";
$lang['goUsers_gotProblemInRetrievingGroups'] = "Consiguió un problema en grupos recuperar contacto con el administrador";
$lang['goUsers_userAddedToActiveGroup'] = "Usuario añadió al grupo activo";
$lang['goUsers_errorNoUserIsLoggedIn'] = "Error ningún usuario ha iniciado sesión";
$lang['goUsers_errorNoUserIdPassed'] = "Error: No identificación de usuario pasado";
$lang['goUsers_destroySessionAndRedirectToLogin'] = "destruir sesión y redirigir para iniciar sesión";
$lang['goUsers_emergencyLogoutComplete'] = "Cierre de sesión de emergencia maquillaje completo navegador agente esté cerrada";
$lang['goUsers_problemInAttempt'] = "Problema en el intento de agente cerrar sesión";
$lang['goUsers_isNotLoggedIn'] = "No está conectado";
$lang['goUsers_errorSomethingWentWrongWhileSavingUser'] = "Error: Algo salió mal al guardar el usuario";
$lang['goUsers_successBatchActionComplete'] = "Éxito: acción completa por lotes";
$lang['goUsers_errorNoUsersSelected'] = "Error: No hay usuarios seleccionados";
$lang['goUsers_successUserCopyComplete'] = "Éxito: copia completa del usuario";
$lang['goUsers_errorUnknownAction'] = "Error: Acción desconocida";
$lang['goUsers_youDontHavePermissionTo'] = "Usted no tiene permiso para";
$lang['goUsers_thisRecords'] = "este registro(s)";
$lang['goUsers_errorOnly150Agents'] = "Error: sólo 150 agentes están permitidos. Por favor, póngase en contacto con nuestro equipo de soporte para agregar más agentes. gracias";
$lang['goUsers_youDontHavePermissionToViewThisRecords'] = "Usted no tiene permiso para ver este registro(s)";
$lang['goUsers_somethingWentWrongContactYourSupport'] = "Algo salió mal en contacto con el Soporte";
$lang['goUsers_invalidUserFormat'] = "Formato de usuario no válido";
$lang['goUsers_invalidPassword'] = "Contraseña no válida";
$lang['goUsers_errorUserGroupMustNotBeEmpty'] = "Error: Grupo de usuarios no puede estar vacío";
$lang['goUsers_errorConditionsAreEmptyOrNotAnArray'] = "Error: condiciones están vacías o no un array";
$lang['goUsers_errorTableNotExistOrFieldsetEmpty'] = "Error: no existe la tabla o conjunto de campos vacía!";
$lang['goUsers_conditionsMustBeAnArray'] = "Las condiciones deben ser una matriz";
$lang['goUsers_somethingWentWrongSorry'] = "Algo salió mal lo siento";
$lang['goUsers_errorOnUpdatingUser'] = "Error en la actualización de usuario!";
$lang['goUsers_updateSuccessful'] = "Actualización de éxito!";
$lang['goUsers_groupConfiguration'] = "Configuración del grupo";
$lang['goUsers_groupName'] = "Nombre del grupo";
$lang['goUsers_groupLevel'] = "Nivel de Grupo";
$lang['goUsers_addAccess'] = "Añadir acceso";
$lang['goUsers_removeAccess'] = "Retire acceso";
$lang['goUsers_somethingWentWrongOnCreatingNewGroup'] = "Algo salió mal en la creación de nuevo grupo";
$lang['goUsers_somethingWentWrongOnSavingNewGroup'] = "Algo salió mal en el ahorro de grupo nuevo";
$lang['goUsers_saved'] = "Guardado";
$lang['goUsers_fieldsAreEmpty'] = "Los campos están vacíos";
$lang['goUsers_wrongConditionFormat'] = "Formato condición incorrecto";
$lang['goUsers_itemDeleted'] = "Elemento eliminado";
$lang['goUsers_mustHaveConditionToDeleteAnItem'] = "Debe tener condición para eliminar un elemento";
$lang['goUsers_errorOnPassingUserAccountOrUserGroup'] = "Error al pasar cuenta de usuario o grupo de usuarios";
$lang['goUsers_displaying'] = "Viendo del";
$lang['goUsers_to'] = "a";
$lang['goUsers_of'] = "de";
$lang['goUsers_warning1'] = "ADVERTENCIA: Al crear usuarios adicionales con extensión telefónica le pondrá por encima del límite de";
$lang['goUsers_warning2'] = ". Eso significa que los usuarios recién añadidos ya no tienen extensiones de teléfono añadidos con ellos.";
$lang['goUsers_clearSearch'] = "[Borrar la búsqueda]";




// banners
$lang['go_dashboard_banner']	= 'Dashboard';
$lang['go_users_banner']		= 'Users';
$lang['go_campaign_banner']		= 'Campaigns';
$lang['go_lists_banner']		= 'Lists';
$lang['go_scripts_banner']		= 'Scripts';
$lang['go_inbound_banner']		= 'Inbound';
$lang['go_voicefile_banner']	= 'Voice Files';
$lang['go_calltimes_banner']	= 'Call Times';
$lang['go_carriers_banner']		= 'Carriers';
$lang['go_phones_banner']		= 'Phones';
$lang['go_tenants_banner']		= 'Multi-Tenant';
$lang['go_servers_banner']		= 'Servers';
$lang['go_settings_banner']		= 'System Settings';
$lang['go_usergroups_banner']	= 'User Groups';
$lang['go_voicemails_banner']	= 'Voicemails';
$lang['go_reports_banner']		= 'Reports & Analytics';

//HEADER - go_dashboard_header.php
$lang['go_Hello'] = "Hola";
$lang['go_Logout'] = "Cerrar sesión";
$lang['go_Edityourprofile'] = "Edite su perfil";
$lang['go_ModifyUser'] = "Modificar Usuario";
$lang['go_AgentID_'] = "Agente ID";
$lang['go_Password_'] = "Contraseña";
$lang['go_FullName_'] = "Nombre Completo";
$lang['go_PhoneLogin'] = "Teléfono ingreso";
$lang['go_PhonePass'] = "Phone Pass";
$lang['go_SUBMIT'] = "ENVIAR";
$lang['go_Showonscreen'] = "Mostrar en pantalla";
//-end

//go_site.php
$lang['go_Dashboard'] = "Salpicadero";
$lang['go_Clicktotoggle'] = "Haga clic para alternar";
$lang['go_Refreshthiswidget'] = "Actualizar este widget";
$lang['go_TodayStatus'] = "Hoy Status";
$lang['go_AccountInformation'] = "Información De La Cuenta";
$lang['go_GOAnalytics'] = "GO Analítica";
$lang['go_Agents_LeadsStatus'] = "Agentes & Cables Estado";
$lang['go_Agents_Phones'] = "Agentes y Teléfonos";
$lang['go_ClusterStatus'] = "Estado del Cluster";

$lang['go_ServerStatistics'] = "Estadísticas del servidor";
$lang['go_SystemVitals'] = "Vitales del Sistema";
$lang['go_Hardware'] = "Hardware";
$lang['go_MemoryUsage'] = "Uso de la memoria";
$lang['go_Filesystems'] = "Los sistemas de ficheros";

$lang['go_SystemServices'] = "Servicios Del Sistema";
$lang['go_SERVICE'] = "SERVICIO";
$lang['go_Telephony'] = "Telefonía";

//GoAutodial Community & Forum
$lang['go_autodialComForum'] = "GoAutoDial Comunidad y Foro";

//GoAutodial News
$lang['go_autodialNews'] = "GoAutoDial Noticias";

//go_dashboard_sippy.php
$lang['go_Balance'] = "Equilibrio";
$lang['go_RemainingMinutes'] = "Minutos Para El Final";
$lang['go_RemainingMinutesTooltip'] = "Los minutos restantes se basan en los Estados Unidos y Canadá la tasa de llamadas.";
$lang['go_ClickheretosignupandactivateGoAutoDialsJustGoVoIPcarrier'] = "Haga clic aquí para registrarse y activar portador JustGoVoIP de GoAutoDial";
$lang['go_ClickheretologintoyourJustGoVoipaccountandloadcredits'] = "Haga clic aquí para acceder a su cuenta JustGoVoip y créditos de carga";
$lang['go_Signupforfree'] = "Regístrese para recibir gratis";
$lang['go_60minutes'] = "60 minutos";
$lang['go_SignupforfreeTooltip'] = "Haga clic aquí para registrarse y activar portador JustGoVoip de GoAutoDial.";
$lang['go_CarrierStatus'] = "El estado de portador";
$lang['go_AccountNumber'] = "Número De Cuenta";

$lang['go_AccountInformations'] = "Informaciones Cuenta";
$lang['go_Firstname_'] = "Nombre de pila";
$lang['go_Lastname_'] = "Apellido";
$lang['go_Phone_'] = "Teléfono";
$lang['go_Address_'] = "Dirección";
$lang['go_City_'] = "Ciudad";
$lang['go_State_'] = "Estado";
$lang['go_ZipCode'] = "Código Postal";
$lang['go_Zip'] = "Cremallera";
$lang['go_Country_'] = "País";

$lang['go_CarrierInformation'] = "Información Carrier";
$lang['go_Carriername'] = "Nombre del transportista";
$lang['go_CarrierID'] = "Carrier ID";
$lang['go_WebUsername'] = "Web Nombre de usuario";
$lang['go_WebPassword'] = "Web Password";
$lang['go_VoIPUsername'] = "VoIP Nombre de usuario";
$lang['go_VoIPPassword'] = "VoIP Contraseña";
$lang['go_show'] = "show";
$lang['go_hide'] = "ocultar";
$lang['go_Clickheretohide'] = "Haga clic aquí para ocultar";
$lang['go_MinutesremainingisbasedonUSandCanadacallrate'] = "Los minutos restantes se basan en los Estados Unidos y Canadá tarifa de llamadas";

//go_account_get_logins.php
$lang['go_NumberofAgents'] = "Número de agente(s)";
$lang['go_Agents'] = "Agentes";
$lang['go_URLResources'] = "Recursos URL";
$lang['go_AgentLoginURL'] = "Conexión del agente URL";
$lang['go_SIP_ServerDomain'] = "SIP / servidor de dominio";
$lang['go_Clickheretoshowmore'] = "Haga clic aquí para ver más";

//go_cluster_status.php
$lang['go_SERVERID'] = "SERVIDOR ID";
//$lang['go_DESCRIPTION'] = "";
$lang['go_SERVERIP'] = "SERVIDOR IP";
//$lang['go_STATUS'] = "";
$lang['go_LOAD'] = "CARGA";
$lang['go_CHANNELS'] = "CANALES";
$lang['go_DISK'] = "DISCO";
$lang['go_TIME'] = "TIEMPO";
$lang['go_VERSION'] = "VERSIÓN";


//go_dashboard_sales.php
$lang['go_Sales'] = "Venta";
$lang['go_TotalSales'] = "Ventas Totales";
$lang['go_InboundSales'] = "Ventas Entrantes";
$lang['go_OutboundSales'] = "Ventas de Salida";
$lang['go_INSales_Hour'] = "EN Ventas / Hora";
$lang['go_OUTSalesHour'] = "OUT Ventas / Hora";

//go_dashboard_calls.php
$lang['go_Calls'] = "Llamadas";
$lang['go_CallsRinging'] = "Llame(s) Timbre";
$lang['go_CallsinOutgoingQueue'] = "Las Llamadas en Cola de Salida";
$lang['go_CallsinIncomingQueue'] = "Las Llamadas en Cola de Entrada";
$lang['go_LiveInbound'] = "Vivo Inbound";
$lang['go_LiveOutbound'] = "Vivo Outbound";
$lang['go_INCallsaMinute'] = "EN Call(s)> a Minuto";
$lang['go_OUTCallsaMinute'] = "Call OUT(s)> a Minuto";
$lang['go_TotalCalls'] = "Total de Llamadas";
$lang['go_Clicktoseecallsbeingplaced'] = "Haga clic para ver las llamadas de ser colocado";

//go_dashboard_drop_percentage.php
$lang['go_DroppedCallPercentage'] = "Abandonado Call Porcentaje";
$lang['go_DroppedPercentage'] = "Porcentaje Abandonado";
$lang['go_DroppedCalls'] = "Llamadas Desconectadas";
$lang['go_AnsweredCalls'] = "Llamadas Contestadas";
$lang['go_Clicktoseethelistof'] = "Haga clic para ver la lista de";

//go_dashboard_agents.php
$lang['go_AgentsResources'] = "Agentes de Recursos";
$lang['go_AgentsonCall'] = "Agente (s) en Llamada";
$lang['go_AgentsonPaused'] = "Agente (s) en Pausa";
$lang['go_AgentsWaiting'] = "Agente (s) de Espera";
$lang['go_TotalAgentsOnline'] = "Agentes Total en Línea";
$lang['go_Clicktomonitoragents'] = "Haga clic para monitorear agentes";

//go_dashboard_leads.php
$lang['go_LeadsResources'] = "Conduce Recursos";
$lang['go_LeadsinHopper'] = "Lidera en Hopper";
$lang['go_DialableLeads'] = "Leads Dialable";
$lang['go_TotalActiveLeads'] = "Total de Ofertas Activas";
$lang['go_CampaignsResources'] = "Campañas Recursos";

//go_call_realtime.php
$lang['go_NOCALLSAVAILABLE'] = "NO LLAMADA DISPONIBLES";
$lang['go_CallMonitoring'] = "Monitoreo de llamadas";
$lang['go_LEGEND'] = "LEYENDA";
$lang['go_OutboundCalls'] = "Llamadas Salientes";
$lang['go_InboundCalls'] = "Las Llamadas Entrantes";

//go_realtime.php
$lang['go_NOAGENTSLOGGEDIN'] = "NO AGENTES CONECTADOS";
$lang['go_AgentMonitoring'] = "Monitoring Agent";
$lang['go_clickagentnametolistenbarge'] = "haga clic en nombre del agente para escuchar / barcaza";
$lang['go_RemoteAgentsarenotshownontheabovelist'] = "Agentes remotos no se muestran en la lista anterior";
$lang['go_WaitingForCall'] = "Esperando la llamada";
$lang['go_OnCall'] = "On Call";
$lang['go_OnPause'] = "En Pausa";
$lang['go_DeadCall'] = "Call Dead";

//go_dropped_calls.php
$lang['go_ACTIVECAMPAIGNSTATISTICS'] = "ESTADÍSTICAS ACTIVA CAMPANA";
$lang['go_ACTIVEINGROUPSTATISTICS'] = "ACTIVO ENDOGRUPO ESTADÍSTICAS";

//go_values.php
$lang['go_FAILED'] = "FALLADO";
$lang['go_SUCCESS'] = "SUCCESS";
$lang['go_List'] = "Lista";
$lang['go_modified'] = "modificado";
$lang['go_deleted'] = "eliminado";
$lang['go_notactivated'] = "no activado";
$lang['go_activated'] = "activado";
$lang['go_deactivated'] = "desactivado";
$lang['go_notdeactivated'] = "no desactivado";
$lang['go_SUBTOTALS'] = "SUBTOTALES";
$lang['go_Clicktoviewreports'] = "Haga clic para ver los informes";
$lang['go_Examplecustomform'] = "Formulario personalizado Ejemplo";
$lang['go_ERRORYoumustenterafieldlabelfieldnameandfieldsize'] = "ERROR: Debe introducir una etiqueta de campo, nombre del campo y el tamaño del campo";
$lang['go_ERRORFieldalreadyexistsforthislist'] = "ERROR: El campo ya existe para esta lista";
$lang['go_fielddeleted'] = "campo eliminada";
$lang['go_ERRORYoucannotcopyfieldstothesamelist'] = "ERROR: No es posible copiar los campos a la misma lista";
$lang['go_ERRORSourcelisthasnocustomfields'] = "ERROR: lista Fuente no tiene campos personalizados.";
$lang['go_ERRORTabledoesnotexist'] = "ERROR: La tabla no existe";
//page5

//go_copycustomfield.php
$lang['go_CustomFieldWizard__CreateCustomField'] = "Asistente Custom Campo >> Crear campos personalizados";
$lang['go_CustomFieldWizard__CopyCustomField'] = "Custom Campo Asistente >> Copia de campos personalizados";
$lang['go_CustomFieldWizard__CreateCopyCustomField']="Asistente Custom Campo »Crear / Copia de campos personalizados";
$lang['go_CreateCustomField'] = "Crear campos personalizados";
$lang['go_CopyFieldstoAnotherList'] = "Copie campos a otra lista";
$lang['go_ListIDtoCopyFieldsFrom'] = "Lista de ID de copia Campos De:";
$lang['go_CopyOption'] = "Opción de copia";
$lang['go_Youdonthavepermissiontocreatethisrecords'] = "Usted no tiene permiso para crear este disco";
$lang['go_Youdonthavepermissiontoupdatethisrecords'] = "Usted don \ 't tiene permiso para actualizar este registro (s)";
$lang['go_AreyousureyouwanttodeletetheselectedDNCnumber'] = "Seguro que quieres eliminar el número seleccionado DNC?";


$lang['go_ListIDisarequiredfield'] = "Lista de diámetro interno es un campo obligatorio";
$lang['go_Phonenumber'] = "número de teléfono";
$lang['go_fromtheDNClist']= "de la lista de DNC";

$lang['go_Labelisarequiredfield'] = "Label es un campo obligatorio";
$lang['go_Nameisarequiredfield'] = "Nombre es un campo obligatorio";
$lang['go_Rankisarequiredfield'] = "Rango es un campo obligatorio";
$lang['go_Orderisarequiredfield']= "El orden es un campo obligatorio";
$lang['go_Fieldmaxisarequiredfield'] = "El campo max es un campo obligatorio";
$lang['go_Fieldsizeisarequiredfield'] = "El tamaño del campo es un campo obligatorio";
$lang['go_Labelshouldnotbeequalto'] = "La etiqueta no debe ser igual a";

$lang['go_SUCCESSCustomFieldAdded'] = "ÉXITO: Custom Campo Agregado";
$lang['go_CustomField'] = "campo personalizado";
$lang['go_Changedate'] = "Cambiar la fecha";


//go_list.php

$lang['go_Confirmtodeletethelist'] = "Confirme que quiere eliminar la lista";
$lang['go_andallofitsleads'] = "y todos sus clientes potenciales?";

$lang['go_ShowLists'] = "Mostrar Listas";
$lang['go_Lists'] = "Listas";
$lang['go_ListsTooltip'] = "Sabías? Las campañas pueden utilizar la lista múltiple. Esto le permite una mayor libertad para elegir los números de teléfono para llamar a base de la lista que desee activa en la campaña.";
$lang['go_CreateNewList'] = "Crear Nueva Lista";
$lang['go_CreateNewField'] = "Crear Nuevo Campo";
$lang['go_SearchLists'] = "Buscar Listas";
$lang['go_AddDeleteDNCNumbers'] = "Añadir / Borrar números DNC";
$lang['go_SearchForALead'] = "Buscar Un Plomo";
$lang['go_ShowListsTabTooltip'] = "Mostrar ficha Listas - muestra todo de ID lista creada en la cuenta junto con la información pertinente relativa a cada ID de lista.";
$lang['go_CustomFieldsTabTooltip'] = "Campos personalizados pestaña - Permitir que el administrador para agregar un campo en su archivo de plomo. Este campo mostrará en las páginas web de agente durante una llamada en vivo dándoles datos adicionales sobre el número de teléfono que se llama. Agentes tendrán que pulsar el botón de formulario personalizado para ver esta información.";
$lang['go_CustomFields'] = "Los campos Personalizados";
$lang['go_LoadLeads'] = "Leads de Carga";
$lang['go_DNCNumbers'] = "Números de DNC";
$lang['go_CallRecordingsLeadSearch'] = "Llame Grabaciones y búsqueda Lead";
$lang['go_Process'] = "Proceso";
$lang['go_CopyCustomField'] = "Copia campo Personalizado";
$lang['go_ListIDtoCopyFieldsFrom'] = "Lista de ID de copia Campos De";
$lang['go_CopyFieldstoAnotherList'] = "Copie Campos a Atra Lista";
$lang['go_CopyOption'] = "Opción de Copia";
$lang['go_APPEND'] = "APPEND";
$lang['go_UPDATE'] = "ACTUALIZACIÓN";
$lang['go_REPLACE'] = "REEMPLAZAR";
$lang['go_ListIDdefinesthelistIDthatwillcontaintheleadfile'] = "Lista ID - define el ID de la lista que contendrá el archivo de plomo.";
$lang['go_Labels'] = "Etiquetas";
$lang['go_Rank'] = "Rango";
$lang['go_Order'] = "Orden";
$lang['go_Position'] = "Posición";
$lang['go_Options'] = "Opciones";
$lang['go_OptionPosition'] = "Opción Posición";
$lang['go_TypeTooltip'] = "Tipo - Define el tipo de datos que se mostrará en el formulario personalizado.";
$lang['go_FieldSize'] = "Tamaño del campo";
$lang['go_FieldSizeTooltip'] = "Tamaño del campo - alows administrador por lo que el tamaño del campo que aparecerá en la página del agente. El valor se basa en el th número de caracteres (es decir, 8 = 8 caracteres de longitud).";
$lang['go_FieldMax'] = "El Campo Max";
$lang['go_FieldMaxTooltip'] = "El campo Max - permite administrador para establecer el número máximo de caracteres para un campo. Si el campo Max es superior Tamaño del campo se verán los caracteres adicionales fuera de la caja de campo en la página web del agente.";
$lang['go_FieldDefault'] = "El Campo Predeterminado";
$lang['go_FieldRequired'] = "Campo Obligatorio";
$lang['go_ListWizard__CreateNewList'] = "Asistente Lista de producto » Crear nueva lista";
$lang['go_numericonly'] = "sólo numérico";
$lang['go_ListName'] = "Nombre de La Lista";
$lang['go_alphanumericonly'] = "alfanumérica única";
$lang['go_ListDescription'] = "Lista Descripción";
$lang['go_ResetTimes'] = "Restablecer Tiempos";
$lang['go_ResetLeadCalledStatus'] = "Restablecer Lead-Called-Estado";
$lang['go_NAME'] = "NOMBRE";
$lang['go_NAMETooltip'] = "Nombre - Puede ser editado para permitir administración para dar una breve descripción de la lista.";
$lang['go_Cannotdelete'] = "No se puede eliminar";
$lang['go_Download'] = "Descargar";
$lang['go_VIEWINFOFORLIST'] = "LISTA PARA VER INFO";
$lang['go_Norecordsfound'] = "Ningún registro(s) encontrado";
$lang['go_Lastcalldate'] = "Última fecha de convocatoria";
$lang['go_CAMPAIGNASSIGNED'] = "CAMPAÑA ASIGNADO";
$lang['go_CUSTOMFIELDS'] = "LOS CAMPOS PERSONALIZADOS";
$lang['go_Leadsfile'] = "Conduce archivo";
$lang['go_LeadsfileTooltip'] = "Navegar Button - permite administrador para cargar un archivo de plomo situada en la unidad local o de red.";
$lang['go_ListIDTooltips'] = "Lista ID - define el ID de la lista que contendrá el archivo de plomo.";
$lang['go_PhoneCode'] = "Código del Teléfono";
$lang['go_PhoneCodeTooltip'] = "Código de teléfono - especifica el país donde se encuentran los números de teléfono en su archivo de plomo.";
$lang['go_LoadfromLeadFile'] = "Cargar Desde Archivo Plomo";
$lang['go_IfyouselectLoadfromLeadFilesbesuretocheckyourphonecodefromyourfile'] = "Si selecciona Cargar archivos plomo, asegurate de revisar el código de teléfono de su archivo";
$lang['go_DuplicateCheck'] = "Duplicar Registro";
$lang['go_TimeZone'] = "Huso Horario";
$lang['go_DuplicateCheckTooltip'] = "Duplicar Check - Comprobará los números de teléfono en el archivo de plomo y Referencia cruzada con todos los números de teléfono en una campaña específica o en todas ID lista.";
$lang['go_TimeZoneTooltip'] = "Zona horaria - afectará a los ajustes de tiempo de llamada de su campaña. Selección de Código de país y Código de área Sólo fijará los ajustes de tiempo de llamadas basadas en el código del número de teléfono de larga distancia. Código Postal Primero será basado en el código postal del número de teléfono (se requiere el código postal de Campo), Propietario Tiempo Primer Código será basada en la zona horaria establecida en el campo del archivo de carga.";
$lang['go_PHONE'] = "TELÉFONO";
$lang['go_FULLNAME'] = "NOMBRE COMPLETO";
$lang['go_SearchAltPhone'] = "Buscar teléfono";
$lang['go_FirstName'] = "Nombre De Pila";
$lang['go_LastName'] = "Apellido";

$lang['go_SHOWADVANCEFIELDS'] = "MOSTRAR ADVANCE CAMPOS";
$lang['go_Customfieldswillshowhereifyouhaveenableditonthelistidyouprovided'] = "Los campos personalizados se mostrarán aquí si se ha habilitado en el id lista que ya ha proporcionado";
$lang['go_Checkthisboxifyouwanttoshowtheresult'] = "Marque esta casilla si desea mostrar el resultado.";
$lang['go_FROM'] = "DESDE";
$lang['go_TO'] = "A";
$lang['go_OPTION'] = "OPCIÓN";
$lang['go_Typethenumberatthetoprightsearchbox'] = "Escriba el número en el cuadro de búsqueda arriba a la derecha";

$lang['go_LEADID'] = "LEAD ID";
$lang['go_LASTAGENT'] = "ÚLTIMA AGENTE";
$lang['go_LeadSearchOptions'] = "Plomo Opciones de Búsqueda";
$lang['go_searchwdate'] = "Buscar con fecha";
$lang['go_LeadID'] = "ID Plomo";

$lang['go_LastAgent'] = "última Agente";
$lang['go_Comments'] = "Comentarios";
$lang['go_Clicktabstoswapbetweencontentthatisbrokenintologicalsections'] = "Haga clic en las pestañas para intercambiar entre el contenido que se divide en secciones lógicas";
//jin
$lang['go_RecordingsforthisLead'] = "Grabaciones de este plomo";
$lang['go_Location'] = "Ubicación";
$lang['go_Seconds'] = "Segundos";
$lang['go_DateTime'] = "Fecha / Hora";
$lang['go_Group'] = "Grupo";
$lang['go_Talk'] = "Hablar";
$lang['go_Wait'] = "Esperar";
$lang['go_Pause']= "Pausa";
$lang['go_AgentLogRecordsforthisLead'] = "Agente expedientes de registro para este plomo";
$lang['go_Length'] = "Longitud";
$lang['go_HangupReason'] = "Razón Colgar";
$lang['go_CloserRecordsforthisLead'] = "Los registros más estrechas de este plomo";
$lang['go_CallstothisLead'] = "Las llamadas a este Plomo";
$lang['go_OtherInfo'] = "Otros Info";
$lang['go_Leadsearchbydaterangeislimitedto60daysonly'] = "Búsqueda plomo por rango de fechas se limita a sólo 60 días";
$lang['go_LeadInformation'] = "Información sobre el Plomo";
$lang['go_PhoneNumber'] = "Número De Teléfono";
$lang['go_AltPhone'] = "Alt. Teléfono"; 
$lang['go_ModifyVicidialLogs'] = "Modificar Vicidial Logs";
$lang['go_ModifyAgentLogs'] = "Modificar Registros de Agente";
$lang['go_ModifyCloserLogs'] = "Modificar Registros Closer";
$lang['go_AddCloserLogRecord'] = "iAñadir Más Cerca de Escritura en Registro";
$lang['go_HIDEADVANCEFIELDS'] = "CAMPOS ADVANCE OCULTAR";
$lang['go_SHOWADVANCEFIELDS'] = "MOSTRAR ADVANCE CAMPOS";

$lang['go_autogenerated'] = "auto generada";

//go_script_main_ce.php
$lang['Script'] = "Guión";
$lang['go_AddNewScript'] = "Añadir Nuevo script"; 
$lang['go_Advance'] = "Avanzar";
$lang['go_ModifyScript'] = "Modificar Secuencias De Comandos";
$lang['go_ScriptID_'] = "Guión ID:";
$lang['go_ScriptName_'] = "Nombre de Script:";
$lang['go_ScriptComments_'] = "Guión Comentarios:";
$lang['go_ScriptText_'] = "Guión de Texto:";
$lang['go_Preview'] = "Preestreno";
$lang['go_Previewscript_'] = "Guión Prevista";
$lang['go_ScriptWizard'] = "Asistente de Escritura";
$lang['go_ScriptType'] = "Guión Tipo";
$lang['go_Pleaseenteratleast3characterstosearch'] = "Introduzca por lo menos 3 caracteres para la búsqueda";
$lang['go_Default'] = "Defecto";

//go_script_ce.php
$lang['go_Youdonthavepermissiontoviewthisrecords'] = "Usted no tiene permiso para ver este registro (s)";
$lang['go_scripts'] = "guiones";
$lang['go_ErrorNoscripttoprocess'] = "Error: No script para procesar";
$lang['go_ErrorSomethingwentwrongwhiledeletingdata'] = "Error: Hubo un inconveniente durante la eliminación de datos";
$lang['go_SuccessItemdeletedcomplete'] = "Éxito: El artículo borrado completo";
$lang['go_ErrorYouarenotallowedtodeletethis'] = "Error: No tienes permiso para eliminar este";
$lang['go_ErrorEmptyscriptid'] = "Error: scriptid vacío";
$lang['go_Insert'] = "Insertar";
$lang['go_ChooseCampaign'] = "Elija Campaña";
$lang['go_ScriptPreview'] = "Guión Prevista";
$lang['go_Save'] = "Guardar";
$lang['go_Accounts'] = "Cuentas";
$lang['go_Language'] = "Idioma:";
$lang['go_Description_Comments'] = "Descripción/Comentarios";
$lang['go_Closing_EndMessage'] = "Clausura Mensaje/End";
$lang['go_Post_EndURL'] = "Publicar/URL End";
$lang['go_URLDescription'] = "URL Descripción";
$lang['go_WouldyouliketoconfigureyoursurveyquestionsoryourLimeSurveysurveyinadvancemodenow'] = "Le gustaría configurar sus preguntas de la encuesta o encuesta <br/> LimeSurvey en modo de avance de ahora?";
$lang['go_ErrorEmptydatavariables'] = "Error: variables de datos vacías!";
$lang['go_SuccessNewlimesurveycreated'] = "Éxito: Nueva limesurvey creado";
$lang['go_Erroronsavingdatacontactyoursupport'] = "Error al guardar datos de contacto de su apoyo";
$lang['go_Errornodatatoprocess'] = "Error: no hay datos para procesar";
$lang['go_AutoGenerated'] = "Generado-Auto";
$lang['go_Errorpassingnotanobjectvariable'] = "Error: no pasa una variable de objeto";
$lang['go_ErrorPassingemptydatacontactyoursupport'] = "Error: Pasar datos vacíos en contacto con su apoyo";
$lang['go_ErrorPleaseclickyourquestion'] = "Error: Por favor, haga clic en su pregunta";
$lang['go_ErrorYouhavenogroupinlimesurveycontactyoursupportplease'] = "Error: No tienes ni grupo en limesurvey en contacto con su apoyo por favor";
$lang['go_ErrorSomethingwentwrongwhilesavingnewquestion'] = "Error: Algo salió mal al guardar nueva pregunta";
$lang['go_SuccessSurveysuccessfullymodified'] = "Éxito: Encuesta modificado con éxito";
$lang['go_ErrorEmptyrawdatainsavinglimesurveyconfigs'] = "Error: datos brutos vacías en salvar configuraciones de LimeSurvey";
$lang['go_ErrorUnknownaction'] = "Error: Acción Desconocida";
$lang['go_WarningYoudonthavepermissionto'] = "Advertencia: Usted no tiene permiso para";
$lang['go_thisrecords'] = "este registro(s)";

//go_script.php
$lang['go_Erroronsavingdata'] = "Error en los datos de ahorro";
$lang['go_ErrorPleasecheckyourvariables'] = "Error: Por favor, cambia las variables";
$lang['go_ErrorPleasecompleteyourdata'] = "Error: Por favor, complete sus datos";
$lang['go_ErrorEmptyvariables'] = "Error: las variables vacías";
$lang['go_Unfinishedpart'] = "Parte Inacabada";
$lang['go_Erroronsavingdatain'] = "Error al guardar los datos en";
$lang['go_Erroronsavingdatainlimesurvey'] = "Error al guardar datos en limesurvey";
$lang['go_ErrorNousersubmitted'] = "Error: No presentado usuario";
$lang['go_ErrorEmptyrawdatawhileaddingnewscript'] = "Error: datos brutos vacíos mientras que la adición nuevo guión";
$lang['go_SuccessNewdefaultscriptcreated'] = "Exitos Nuevo script predeterminado creado";
$lang['go_ErrorSomethingwentwrongpleasecontactyoursupport'] = "Error: Algo salió mal por favor póngase en contacto con soportei";

$lang['go_ErrorInvalidscriptidformat'] = "Error: Formato de Identificación del guión no válido";
$lang['go_ErrorInvalidscriptnameformat'] = "Error: la escritura no válida formato de nombre";
$lang['go_ErrorInvalidscripttextformat'] = "Error: Formato de texto de la escritura no válido";

$lang['go_SuccessUpdatesuccessful'] = "Éxito: Actualización exitosa";
$lang['go_ErrorEmptydatafields'] = "Error: campo de datos vacío (s)";


//go_script_elem_ce.php
$lang['go_SCRIPTID'] = "GUION ID";
$lang['go_SCRIPTNAME'] = "NOMBRE GUION";
$lang['go_TYPE'] = "TIPO";
$lang['go_USERGROUP'] = "GRUPO DE USUARIO";
$lang['go_Modifyscript'] = "Modificar la escritura";
$lang['go_Deletescript'] = "Eliminar guión";
$lang['go_EnableSelected'] = "Habilitar seleccionada";
$lang['go_DisableSelected'] = "Desactivar seleccionado";
$lang['go_ALLUSERGROUPS'] = "ALL USER GROUPS";

//go_script_advance_ce.php
$lang['go_Scriptsettings'] = "Ajustes de Scripts";
$lang['go_Addquestions'] = "Añadir preguntas";
$lang['go_NoteExistingLimesurveyscriptscantbeeditedCreateanewoneifyouneedtoaddeditquestions'] = "Nota: las secuencias de comandos existentes limesurvey no se pueden editar. Crear una nueva si es necesario añadir / editar preguntas";
$lang['go_SurveyURL'] = "URL de la Encuesta";
$lang['go_SurveyName']= "Nombre de la encuesta";
$lang['go_SurveyDescription'] = "Encuesta Descripción";
$lang['go_WelcomeMessage'] = "Mensaje de Bienvenida";
$lang['go_EndMessage'] = "Mensaje de Fin";
$lang['go_BaseLanguage'] = "Base Idioma";
$lang['go_Administrator'] = "Administrador";
$lang['go_AdminEmail'] = "Admin Email";
$lang['go_EndURL'] = "URL End";
$lang['go_EndURLDescription'] = "Fin URL Descripción";
$lang['go_DecimalSeparator'] = "Separador decimal";
$lang['go_TITLE'] = "TÍTULO";
$lang['go_QUESTION'] = "PREGUNTA";
$lang['go_Question'] = "Pregunta";
$lang['go_MANDATORY'] = "OBLIGATORIO";
$lang['go_Code'] = "Código";
$lang['go_Help'] = "Ayuda";
$lang['go_Type'] = "Tipo";
$lang['go_Mandatory'] = "Obligatorio";
$lang['go_Validation'] = "Validación";
$lang['go_Format'] = "Formato";
$lang['go_Template'] = "Plantilla";
$lang['go_TemplatePreview'] = "Template Preview";
$lang['go_Showwelcomescreen'] = "Mostrar pantalla de bienvenida?";
$lang['go_Navigationdelayseconds'] = "Navegación de retardo segundos";
$lang['go_Showquestionindex_allowjumping'] = "Mostrar índice pregunta / permitirá saltar";
$lang['go_Keyboardlessoperation'] = "Operación sin teclado";
$lang['go_Showprogressbar'] = "Mostrar barra de progreso";
$lang['go_Participantsmayprintanswers'] = "Los participantes pueden imprimir respuestas";
$lang['go_Publicstatistics'] = "Las estadísticas públicas";
$lang['go_Showgraphsinpublicstatistics'] = "Mostrar gráficos en las estadísticas públicas";
$lang['go_AutomaticallyloadURLwhensurveycomplete'] = "Cargar automáticamente URL cuando estudio completo?";
$lang['go_ShowThereareXquestionsinthissurvey'] = "Mostrar 'Hay X preguntas de esta encuesta'";	
$lang['go_Showgroupnameandorgroupdescription'] = "Mostrar nombre del grupo y / o descripción del grupo";
$lang['go_Showquestionnumberandorcode'] = "Mostrar número de la pregunta y / o código";


//go_widget_show_me_more


//header tab [notification]
//show on screen


//CAMPAIGNS
$lang['go_icon32Tooltip']     = "Una <strong> campaña </strong> es una función de cuenta única que le permite modificar y cambiar el comportamiento del sistema de acuerdo a las necesidades de sus clientes .";
$lang['go_Campaigns']         = "Campañas";
$lang['go_CAMPAIGN']	      = "CAMPAÑA";
$lang['go_CAMPAIGNID']        = "IDENTIFICACIÓN CAMPAÑA";
$lang['go_CAMPAIGNNAME']      = "NOMBRE DE LA CAMPAÑA";
$lang['go_DIALMETHOD']        = "MÉTODO DE MARCACIÓN";
$lang['go_PAUSECODES']	      = "CÓDIGOS DE PAUSA";
$lang['go_PAUSECODE']	      = "CÓDIGO PAUSA";
$lang['go_PAUSECODESTooltip'] = "Al hacer clic en el ID de campaña en sí o en el icono de modificación permitirá que el código de pausa para ser editado";
$lang['go_MODIFYCAMPAIGNPAUSECODES']   = "MODIFICAR LOS CÓDIGOS DE PAUSA DE LA CAMPAÑA";
$lang['go_DELETECAMPAIGNPAUSECODES'] = "BORRAR LOS CÓDIGOS DE PAUSA DE LA CAMPAÑA";
$lang['go_MODIFYCAMPAIGNHOTKEYS'] = "MODIFICAR LA CAMPAÑA HOTKEYS";
$lang['go_DELETECAMPAIGNHOTKEYS'] = "ELIMINAR LA CAMPAÑA HOTKEYS";
$lang['go_FILTERID']	      = "ID FILTRO";
$lang['go_FILTERNAME']	      = "NOMBRE DEL FILTRO";
$lang['go_MODIFYFILTERID']    = "MODIFICAR ID FILTRO";
$lang['go_DELETEFILTERID']    = "BORRAR ID FILTRO";
$lang['go_LeadRecyclingWizard_CreateNewLeadRecycling'] = "Plomo Asistente Reciclaje >> Crear nuevo reciclaje de plomo";
$lang['go_LeadRecyclingTab']  = "<b> El plomo reciclado Tab </b> - Permite la administración para establecer las disposiciones del sistema reciclará automáticamente en la tolva cuando el límite de tiempo establecido ha terminado. Estas disposiciones serán llamados de nuevo cuando se reciclan en la tolva.";
$lang['go_LeadRecycling']     = "Reciclaje de Plomo";
$lang['go_NONE'] 	      = "NINGUNO";
$lang['go_STATUS']            = "ESTADO";
$lang['go_ACTION']	      = "ACCIÓN";
// -r active
//-r avtive  selc
//-r deactive selec
//-r del selc

$lang['go_minimumChar'] = "Mínimo de 3 caracteres";
$lang['go_available'] = "Disponible";
$lang['go_notAvailable'] = "No Disponible";

//Campaign >> view info
$lang['go_CampaignID_']			 = "Identificación de Campaña:";
$lang['go_CampaignName_']		 = "Nombre de la Campaña:";
$lang['go_CampaignDescription_']	 = "Descripción de la Campaña:";
$lang['go_AllowInboundAndBlended_']	 = "Permitir entrantes y Mezclas:";
$lang['go_DialMethod_']			 = "Método de marcación:";
$lang['go_DialMethodTooltip']		 = "<center> <b> Método de marcación </b> </center> </br> <b> Manual </b> - El usuario tendrá que hacer clic en el <i> - Dial Siguiente </i> para hacer saliente </br> llamadas. Esto siempre se hace después de una llamada ha sido dispositioned </br> <b> Auto Dial </b> -. Se utiliza para la campaña de tipo de salida. Sistema automáticamente </br>
marcar números de teléfono en el archivo principal. Número de líneas se encuentra en la </br> Nivel marcación automática </br> <b> predictivo </b> -. Se utiliza para la campaña de tipo de salida. El sistema automáticamente </br> calcular el nivel de marcado basado en el porcentaje de la gota. Caída defecto </br> porcentaje es del 3%. Si el porcentaje gota se cumplió o superó, el sistema </br> más abajo del nivel de marcado automático </br> <b> Inbound Man </b> -.. Se utiliza para la campaña de tipo mixto. Agentes obtendrán entrantes </br> llamadas al hacer clic en el botón Reanudar. Las llamadas salientes se realizan por </br> ya sea haciendo clic en el botón \ Dial Siguiente]. O haciendo clic en el enlace de marcación manual </br> en la página web agente";
$lang['go_DialMethodTooltip2']		 = "<center> <b> Dial Auto Nivel </b> </center> </br>
<b> Slow </b> - 1 línea por cada agente disponible </br>.
<b> Normal </b> -. 2 líneas por cada agente disponible </br>
<b> Alta </b> -. 4 líneas por agente disponible </br>
<b> Max </b> -. 6 líneas por agente disponible </br>
<b> Avance </b> - Permite administrador para establecer cómo </br>
se abrirán muchas líneas por agente.";

$lang['go_AutoDialLevel_']		 = "Nivel de marcado automático:";
$lang['go_AutoDialLevelTooltip']	 = "<center> <b> Dial Auto Nivel </b> </center> </br>
<b> Slow </b> - 1 línea por cada agente disponible </br>.
<b> Normal </b> -. 2 líneas por cada agente disponible </br>
<b> Alta </b> -. 4 líneas por agente disponible </br>
<b> Max </b> -. 6 líneas por agente disponible </br>
<b> Max predictivo </ b> -. 10 líneas por cada agente disponible (esto es para Predictive) </br>
<b> Avance </b> - Permite administrador para establecer cómo </br>
se abrirán muchas líneas por agente.";
$lang['go_AnsweringMachineDetection_']   = "Detección Contestador:";
$lang['go_ManualDialPrefix_'] 		 = "Manual Prefijo:";
$lang['go_GetCallLaunch_'] 		 = "Obtener Lanzamiento de llamadas:";
$lang['go_GetCallLaunchTooltip'] 	 = "<b> Obtener Call Lanzamiento </b> - permite la administración para tener automáticamente </br> la ventana emergente de la escritura en la página web del Agente a </br> el inicio de una llamada sin la necesidad de que los agentes hagan clic en su botón correspondiente";
$lang['go_AnsweringMachineMessage_']	 = "Contestador Automático Mensaje:";
$lang['go_AnsweringMachineMessageTooltip']= "<b> Contestar Mensaje máquina </b> - permite administrador para configurar un archivo de voz pregrabado que se jugará cuando </br> el sistema detecta un contestador automático. CPD AMD Acción debe establecerse en Mensaje.";
$lang['go_WaitForSilenceOptions_']	 = "Opciones WaitForSilence:";
$lang['go_WaitForSilenceOptionsTooltip'] = "<b> Espere Silencio </b> - establece el número de milisegundos que el sistema esperará antes de activar </br> El contestador automático de mensajes. Dos ajustes, separadas por un comima, son necesarios para </br> introducirse. Primera configuración detectará la longitud de silencio que esperar (medido en milisegundos) </br> y el otro es el número de veces que se necesita para detectar que antes de jugar el pregrabado </br> archivo de voz.";
$lang['go_AMDSendtoVMexten_'] 		 = "AMD Enviar a VM exten:";
$lang['go_CPDAMDAction_']		 = "Acción CPD AMD:";
$lang['go_CPDAMDActionTooltip'] 	 = "<b> CPD AMD Acción </b> - define lo que el sistema va a hacer cuando se detecta un contestador automático </br> Dispo permitirá que el sistema a disposición la llamada como AA antes de que llegue a un agente.. Mensaje </br> permitirá sistema para auto reproducir un archivo de voz establecido en la configuración del contestador automático de mensajes";
$lang['go_PauseCodesActive_']		 = "Pausa Códigos Activo:";
$lang['go_AvailableOnlyTally_'] 	 = "Disponible sólo Tally:";
$lang['go_Available']                    = "Disponible";
$lang['go_ManualDialFilter_']		 = "Marcación manual Filtro:";
$lang['go_AgentLeadSearch_']		 = "Búsqueda de agente de plomo:";
$lang['go_AgentLeadSearchMethod_']	 = "Agente de Entrega Método de búsqueda:";
$lang['go_NextAgentCall_']		 = "Siguiente Agente Call:";
$lang['go_NextAgentCallTooltip']	 = "<b> Siguiente mail Llamar </b> - define cómo se deben enrutar las llamadas a un agente.";

$lang['go_DIDTFNExtension_'] = "DID/TFN Extensión:";

$lang['go_TransferConfNumber1_']	 = "Número Transfer-Conf 1:";
$lang['go_TransferConfNumber1Tooltip']	 = "<b> Transfer-Conf Número 1 y 2: </b> - almacenará un número de teléfono específico en el D1 y D2 que puede ser </br> utiliza para auto pueblan el número de cuadro de llamar. Esta opción sólo se utiliza durante las llamadas de transferencia.";

$lang['go_TransferConfNumber2_']	 = "Número Transfer-Conf 2:";
$lang['go_TransferConfNumber2Tooltip'] = "<b> Transfer-Conf Número 1 y 2: </b> - almacenará un número de teléfono específico en el D1 y D2 que puede ser </br> utiliza para auto pueblan el número de cuadro de llamar. Esta opción sólo se utiliza durante las llamadas de transferencia.";

$lang['go_3WayCallOutboundCallerID_']	 = "3-Way de llamadas salientes CallerID:";
$lang['go_3WayCallOutboundCallerIDTooltip'] = "<b> 3-Way de llamadas salientes CallerID </b> - define el identificador de llamadas que se utilizará durante una llamada de 3 vías";

$lang['go_3WayCallDialPrefix_']		 = "3-Way Call Marcar prefijo:";
$lang['go_Customer3WayHangupLogging_']	 = "Cliente 3-Way Colgar Registro:";
$lang['go_Customer3WayHangupLoggingTooltip'] = "<b> Cliente 3-Way Colgar Logging </b> - Si se activa esta opción permitirá que el sistema de registro si el cliente colgó </br> durante una llamada de 3 vías. Esta opción también se activará la opción contemplada en el cliente 3-Way Colgar Acción";

$lang['go_Customer3WayHangupSeconds_']	 = "Cliente 3-Way segundos de colgado:";
$lang['go_Customer3WayHangupSecondsTooltip'] = "Segundos <b> Cliente 3-Way de colgado </b> - especifica la cantidad en cuestión de segundos antes de que el sistema se activará </br> el cliente 3-Way Colgar Acción";

$lang['go_Customer3WayHangupAction_']	 = "Cliente 3-Way Colgar Acción:";
$lang['go_AudioChooser']		 = "[ Audio Selector ]";


//campaign >> edit >> [+]advance settings (alert: gofree)
$lang['go_AdvSetAlert1'] = "Algunas de las opciones están desactivadas debido a restricciones de la cuenta de prueba.";
$lang['go_AdvSetAlert2'] = "Para la funcionalidad completa del sistema, puede actualizar la cuenta a cualquiera de nuestros paquetes de nube privada .";
$lang['go_SelectCampaign'] = "--- SELECCIONAR CAMPAÑA ---";

//campaign >> edit >> box
$lang['go_minusADVANCESETTINGS']	 = "[ - CONFIGURACIÓN AVANZADA ]";
$lang['go_plusADVANCESETTINGS']		 = "[ + CONFIGURACIÓN AVANZADA ]";
$lang['go_SAVESETTINGS']		 = "GUARDAR CONFIGURACIÓN";
$lang['go_LISTWITHINCAMPAIGN']		 = "LISTAS EN ESTA CAMPAÑA";
$lang['go_SAVE']			 = "SAVE";
$lang['go_SAVEACTIVELISTCHANGES']	 = "GUARDAR LISTA DE CAMBIOS ACTIVOS";
$lang['go_Thiscampaignhas']		 = "Esta campaña tiene";
$lang['go_activelistsand']		 = "listas de activos y";
$lang['go_inactivelists']		 = "listas inactivos";
$lang['go_AttemptDelay']		 = "Intente Delay";
$lang['go_Shouldbefrom2to720mins12hrs']  = "En caso de ser 2 a 720 minutos (12 horas).";
$lang['go_AttemptMaximum_']		 = "Máxima Intento:";
$lang['go_MaximumAttempts'] 		 = "Los intentos máximos";
$lang['go_PauseCodeWizard__CreateNewPauseCode'] = "Pausa Asistente Código >> Crear nuevo Código Pausa";
$lang['go_HotKeysWizard__CreateNewHotKey'] = "Asistente teclas de acceso rápido >> Crear Nuevo teclas de acceso rápido";
$lang['go_LeadFilterWizard__CreateNewFilter'] = "Asistente para filtros de plomo >> Crear Nuevo Filtro";

$lang['go_leadsinthequeue']		 = "lleva en la cola (dial tolva)";
$lang['go_Viewleadsinthehopperforthiscampaign']	 = "Ver conduce en la tolva para esta campaña";
$lang['go_VLITHFTCtooltip'] = "Al hacer clic en este enlace mostrará todos los números telefónicos cargados actualmente en la tolva";
$lang['go_Logoutallagentswithinthiscampaign']	 = "Salir todos los agentes dentro de esta campaña";
$lang['go_ScriptTooltip']	 	 = "<center> <b> Script </b> </center> Permite administrador para permitir una ventana emergente </br> en la página web del agente durante una llamada en vivo (agente necesita </br> haga clic en el botón <b> guión botón </b>).";

//LIST CAPITAL
$lang['go_LISTID']			 = "LISTA ID";
$lang['go_LISTIDTooltip']		 = "<b> Lista ID - siendo utilizado por la campaña </b> - usted puede cambiar entre listas o combinarlas marcando la - Activo </br> cuadro de la columna. El icono Modificar le permite editar el propio ID Lista";
$lang['go_LISTNAME']			 = "NOMBRE DE LA LISTA";
$lang['go_DESCRIPTION'] 		 = "DESCRIPCIÓN";
$lang['go_LEADSCOUNT']			 = "LLEVA CUENTA";
$lang['go_LEADSCOUNTTooltip']		 = "Conduce Columna Conde - muestra el número total de números de teléfono que se pueden marcar en la lista.";
$lang['go_ACTIVE']			 = "ACTIVO";
$lang['go_LASTCALLDATE']		 = "FECHA ULTIMA LLAMADA";
$lang['go_MODIFY']			 = "MODIFICAR";
$lang['go_PauseCodeCampaignTooltip'] 	 = "<b> Campaña </b> - define la campaña que va a utilizar el código de pausa. Las campañas pueden utilizar varios códigos de pausa </br> pero hay que configurar los códigos de pausa individual";
$lang['go_CarriertouseforthisCampaign']  = "Carrier a utilizar para esta Campaña:";

//Campaign >> edit >> [-] advance settings
//go_CampaignID_
//go_CampaignName_
//go_CampaignDescription_
$lang['go_Active_'] 			= "Activo";
$lang['go_INACTIVE'] 			= "INACTIVO"; 
//go_DialMethod_
//go_AutoDialLevel_
$lang['go_CarrierForCampaign_'] 	= "Carrier a utilizar para esta Campaña:";
$lang['go_Script_'] 			= "Suión:";
$lang['go_CampaignCallerID_'] 		= "Campaña de identificación de llamadas :";
$lang['go_CampaignCallerIDTooltip']	= "<b> Campaña de identificación de llamadas </b> - establece el número de teléfono </br> que se mostrará en la llamada de teléfono del partido.";
$lang['go_CampaignRecording_'] 		= "Grabación de campaña:";
//go_AnsweringMachineDetection_
$lang['go_LocalCallTime_'] 		= "Hora local de llamadas:";
$lang['go_LocalCallTimeTooltip']	= "<b> local Hora Llamar </b> - establece la ventana de tiempo en que serán llamados cables. Esto se basa </br> en el tiempo real en que se encuentra el número de teléfono.";


//Campaign >> edit >> [+] advance settings
$lang['go_CampaignChangeDate_']		= "Campaña Cambiar Fecha:";
$lang['go_CampaignLoginDate_']		= "Campaña Login Fecha:";
$lang['go_CampaignCallDate_']		= "Campaña Fecha de llamadas:";
$lang['go_ParkMusicOnHold_']		= "Parque de la Música en Espera:";
$lang['go_WebForm_']			= "Formulario Web:";
$lang['go_WebFormTooltip'] 		= "<b> Formulario Web </b> - Permite la administración para especificar la página web que se abrirá </br> cuando un agente hace clic en el botón de formulario Web.";
$lang['go_WebFormTarget_'] 		= "Objetivo de Formularios Web";
$lang['go_WebFormTargetTooltip'] 	= "<b> Formulario Web Target </b> - permite administrador para especificar el marco en que se abrirá la web </br> formulario. Sólo aplicable para los navegadores Multi Frame.";
//go_AllowInboundAndBlended_
$lang['go_AllowInboundAndBlendedTooltip'] = "<b> Marcar Estado </b> - Especifica las disposiciones sobre el archivo principal activo (s) en la campaña que </br> el sistema marcará automáticamente. Cualquier disposiciones no incluidas en el estado de línea se </br> No se va a marcar";
$lang['go_ActiveStatusDial']              = "Activo Estado Dial";
$lang['go_ListOrder_'] 			  = "Lista de Pedidos:";
$lang['go_LeadFilter_']			= "Filtro de ejecución:";
$lang['go_ForceResetLeadsonHopper_']	= "Restablecer Fuerza Leads en la tolva:";
$lang['go_ForceResetLeadsonHopperTooltip'] = "<b> Fuerza Restablecer potenciales sobre Hopper </b> -, se borrarán los números de teléfono actuales cargados en la tolva que se </br> la espera de ser marcado. La tolva willautomatically cargar un nuevo conjunto de números después de unos minutos.";
$lang['go_DialTimeout_']		= "Dial Tiempo de espera:";
$lang['go_DialTimeoutTooltip'] 		= "<b> Dial Tiempo de espera </b> - especifica el número de segundos que el sistema intentará marcar un número de teléfono antes de colgar.";

//Displaying page number
$lang['go_Display'] 	      = "Viendo del";
$lang['go_to']		      = "al";
$lang['go_of']	      	      = "de";
//pagination
$lang['go_FirstPage'] 	      = "Ir a la primera página";
$lang['go_PreviousPage']      = "Ir a la página anterior";
$lang['go_PageNumber'] 	      = "Ir a la página";
$lang['go_ViewAllPage']       = "Ver todas las páginas";
$lang['go_NextPage'] 	      = "Ir a la página siguiente";
$lang['go_Lastpage']	      = "Ir a la última página";
$lang['go_ALL']		      = "TODO";
$lang['go_BacktoPaginatedView']= "Volver a Ver Paginado";
$lang['go_BACK']	       = "VOLVER";

//list
$lang['go_listids'] = "id lista";

//tooltip
$lang['go_CampaignTabTooltip']= "<b> Campañas Tab </b> - Proporciona una lista de las campañas creadas en la cuenta y la información pertinente relativa a las campañas.";
$lang['go_MODIFYCAMPAIGN']    = "MODIFICAR CAMPAÑA";
$lang['go_DELETECAMPAIGN']    = "ELIMINAR CAMPAÑA";
$lang['go_DELETE']	      = "ELIMINAR";
$lang['go_VIEWCAMPAIGN']      = "VER INFO DE LA CAMPAÑA";

//search
$lang['go_Search'] 	      = "Búsqueda";
$lang['go_ClearSearch']	      = "[Borrar la Búsqueda]";
$lang['go_NoRecordsFound']    = "Ningún registro (s) encontrado.";

//add new campaign
$lang['go_AddNewCampaign']    = "Añadir nueva campaña";
$lang['go_AddNewCampTooltip'] = "<b> Añadir nueva campaña </b> - Permite la administración para crear una nueva campaña ."; 

$lang['go_AddNewStatus']      = "Agregar Nuevo Estado";
$lang['go_AddNewStatusTooltip'] = "Agregar Nuevo Estado";

$lang['go_AddNewLeadRecycle'] = "Agregar Nuevo Plomo de Reciclaje";
$lang['go_AddNewLeadRecycleTooltip'] = "Agregar Nuevo Plomo de Reciclaje";

$lang['go_AddNewPauseCode']   = "Agregar Nuevo Código Pausa";
$lang['go_AddNewPauseCodeTooltip'] = "<b> Añadir Nuevo Código Pausa </b> - clic en este generará la pausa Asistente Código.";

$lang['go_AddNewHotKey']      = "Añadir Nuevo HotKey";
$lang['go_AddNewHotKeyTooltip'] = "<b> HotKeys </b> - son accesos directos que permiten a un agente a disposición automáticamente la llamada a un pre valor definido pulsando un botón de número. Haga clic en la opción Agregar </br> Nueva Hotkey to initialize the Hotkey Asistente que </br> le ayudará en la creación de un nuevo hotkey para su </br> campaña.";
$lang['go_HotkeyTooltip']     = "<b> Tecla de acceso directo </b> - define la tecla de acceso directo que se utilizará. Las opciones son 1-9.";

$lang['go_Filter']	= "Filtro";
$lang['go_AddNewFilter']      = "Agregar Nuevo Filtro";
$lang['go_AddNewFilterTooltip'] = "Agregar Nuevo Filtro"; 
$lang['go_FilterID_'] = "ID Filter:";
$lang['go_FilterName_']= "Filtra por Nombre:";
$lang['go_FilterComments_'] = "Filtra Comentarios:";
$lang['go_UserGroup_'] = "Grupo de Usuarios:";
$lang['go_Fields_'] = "Campos:";
$lang['go_FilterOptions_'] = "Opciones de Filtro:";
$lang['go_FilterbyDate_'] = "Filtrar por Fecha:";
$lang['go_FilterbyCalledCount_'] = "Filtrar por Conde Llamado:";
$lang['go_FilterbyCountryCode_'] = "Filtrar por País Código";
$lang['go_FilterbyAreaCode_'] = "Filtrar por código de área";
$lang['go_lterbyTimezone_'] = "Iterby Zona horaria";

$lang['go_FilterbyState_'] = "Filtrar por Estado";
$lang['go_SQLPreview_'] = "SQL Prevista";
//filter options
$lang['go_FilterSQL_'] = "Filtro SQL";
$lang['go_ClearSQL'] = "Claro SQL";
$lang['go_Disclamer1'] = "* Nota: El uso incorrecto puede provocar la interrupción del servicio.";
$lang['go_Disclamer2'] = "* Nota:";
$lang['go_Disclamer3'] = "Use at your own risk";

//$lang['go_'] = "";

//Edit Campaign >> Modify List
$lang['go_ChangeDate'] 		= "Cambiar la fecha:";
$lang['go_LastCallDate']	= "Última llamada Fecha:";
$lang['go_Name_']	 		 = "Nombre:";
$lang['go_Description_']		 = "Descripción:";
//go_Campaign_
$lang['go_ResetTime_']			 = "Restablecer tiempos:";
$lang['go_ResetLeadCalledStatus_']	 = "Restablecer plomo Estado Llamado:";
$lang['go_AgentScriptOverride_']	 = "Guión Agente de anulación:";
$lang['go_CampaignCIDOverride_']	 = "Campaña CID Anulación:";
$lang['DropInboundGroupOverride_']	 = "Caída de anulación Grupo Inbound:";
//go_WebForm_
//go_Active_
$lang['go_TransferConfNumberOverride']	 = "Número Transfer-Conf Anulación";
$lang['go_Number']			 = "Número";
$lang['go_STATUSESWITHINTHISLIST']	 = "ESTADOS EN ESTA LISTA";
$lang['go_TIMEZONESWITHINTHISLIST']	 = "ZONAS HORARIAS DENTRO DE ESTA LISTA";
$lang['go_STATUSESWITHINTHELIST']        = "ESTADOS EN LA LISTA";
$lang['go_TIMEZONESWITHINTHELIST']       = "ZONAS HORARIAS DENTRO DE LA LISTA";
//go_STATUS
$lang['go_STATUSNAME']			 = "NOMBRE ESTADO";
$lang['go_CALLED']			 = "LLAMADO";
$lang['go_NOTCALLED']			 = "NO LLAMADA";
$lang['go_NEW']				 = "NUEVO";
$lang['go_SUBTOTAL']			 = "SUBTOTAL";
$lang['go_TOTAL']			 = "TOTAL";
$lang['go_GMTOFFSETNOW']		 = "GMT EMPRESA";
//called
//not called


//free make
//$lang[''] = "";
$lang['go_Upload'] = "Subir";
$lang['go_ListofFilesUploaded_'] = "Lista de los archivos subidos:";
$lang['go_AllowedTransferGroup_'] = "Grupo permitido de transferencia:";
$lang['go_NumberofLines_']	= "Número de líneas:";
$lang['go_Press4DID_'] 		= "Pulse 4 DID:";
$lang['go_Press4Status_']	= "Pulse 4 Estado";
$lang['go_Press4AudioFile_'] 	= "Pulse 4 Audio Archivo";
$lang['go_Press4Digit_'] 	= "Pulse 4 Digit";
$lang['go_Press3DID_'] 		= "Pulse 3 DID";
$lang['go_Press3Status_'] 	= "Pulse 3 Estado";
$lang['go_Press3AudioFile_'] 	= "Pulse 3 Audio:";
$lang['go_Press3Digit_'] 	= "Presione 3 dígitos:";

$lang['go_Press8NotInterestedStatus_']		 = "Pulse 8 Status No hay interés:";
$lang['go_Press8NotInterestedAudioFile_']	 = "Pulse 8 No hay interés de archivo de audio:";
$lang['go_Press8NotInterestedDigit_']		 = "Pulse 8 Digit No hay interés:";
$lang['go_DID_']				 = "DID:";
$lang['go_SurveyDTMFDigits_']			 = "Encuesta dígitos DTMF:";

$lang['go_SurveyCallMenu_'] 			 = "Encuesta Call Menu:";
$lang['go_SurveyMethod_']			 = "Método Encuesta:";
$lang['go_AudioFile_']				 = "Archivo de Audio:";

$lang['go_AssignedDID_TFN_']			 = "Asignada DID / TFN:";
$lang['go_AddADialStatustoCall_']		 = "Añadir un Estado para llamar a:";
$lang['go_AvailableOnlyTally_']			 = "Disponible sólo Tally:";
$lang['go_CampaignRecFilename_']		 = "Campaña Rec Nombre del archivo:";
$lang['go_InboundGroups_']			 = "Grupos entrantes:";
$lang['go_REMOVESTATUS_']			 = "RETIRE ESTADO:";
$lang['go_customerdefinekeypress']		 = "cliente definir pulse la tecla";
$lang['go_Filename'] = "Nombre Del Archivo";

//go_campaign_list.php
$lang['go_ErrorYoudonothavepermissiontomodifythiscampaign'] = "Error: No tienes permiso para modificar esta campaña.";
$lang['go_ErrorYoudonothavepermissiontodeletecampaigns'] = "Error: Usted no tiene permiso para eliminar campañas.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignstatuses'] = "Error: No tienes permiso para modificar los estados de la campaña.";
$lang['go_Erroroudonothavepermissiontodeletecampaignstatuses'] = "Error: Usted no tiene permiso para eliminar estados de campaña.";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignstatuses'] = "Seguro que quieres eliminar los estados de campaña\'s seleccionados?";
$lang['go_ErrorYoudonothavepermissiontodeleteleadrecyclingstatuses'] = "Error: Usted no tiene permiso para eliminar estados de reciclaje de plomo.";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignpausecodes'] = "Seguro que quieres eliminar la campaña seleccionado 'códigos s pausa?";
$lang['go_ErrorYoudonothavepermissiontodeletecampaignpausecodes'] = "Error: Usted no tiene permiso para eliminar los códigos de pausa de campaña.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignpausecodes'] = "Error: No tienes permiso para modificar los códigos de pausa de campaña.";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignhotkeys'] = "Seguro que quieres eliminar las teclas de acceso rápido campaña\'s seleccionados?";
$lang['go_ErrorYoudonothavepermissiontodeletecampaignhotkeys'] = "Error: Usted no tiene permiso para eliminar teclas de acceso rápido de campaña.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignhotkeys'] = "Error: No tienes permiso para modificar teclas de acceso rápido de campaña.";
$lang['go_AreyousureyouwanttodeletethisHotKey'] = "¿Seguro que quieres borrar este teclas de acceso rápido?";
$lang['go_ErrorYoudonothavepermissiontomodifyleadfilters'] = "Error: No tienes permiso para modificar los filtros de plomo.";

$lang['go_Pleasecheckthefollowingerrors'] = "Por favor, consulte el siguiente error (s):";
$lang['go_StatusIDisempty'] = "Estado ID está vacío";
$lang['go_StatusIDisNotAvailable'] = "Estado ID no está disponible";
$lang['go_StatusNameisempty'] = "Estado Nombre está vacío";
$lang['go_Pleaseselectorfillinallthefields'] = "Por favor seleccione o rellenar todos los campos.";
$lang['go_NotAvailable'] = "No Disponible";
$lang['go_ErrorYoudonothavepermissiontomodifyleadrecyclingstatuses'] = "Error: No tienes permiso para modificar los estados de reciclaje de plomo.";
$lang['go_AreyousureyouwanttodeletethisStatus'] = "¿Seguro que quieres borrar este Estado?";
$lang['go_AreyousureyouwanttodeletethisPauseCode'] = "¿Seguro que quieres borrar este Código Pausa?";

$lang['go_FilterIDisempty'] = "ID Filter está vacía";
$lang['go_FilterIDisNotAvailable'] = "ID del filtro no está disponible";
$lang['go_FilterNameisempty'] = "Nombre del filtro está vacía";
$lang['go_UserGroupnotselected'] = "Grupo de Usuarios no seleccionado";

$lang['go_SQLquerystringalreadyexist'] = "Cadena de consulta SQL ya existe.";
$lang['go_PleaseselectanSQLoperatorANDorORtocontinue'] = "Por favor, seleccione un operador SQL 'Y' o 'O' para continuar.";
$lang['go_Minimumof3characters'] = "Mínimo de 3 caracteres.";
$lang['go_PleasecomposeanSQLquery'] = "Por favor componer una consulta SQL.";
$lang['go_NONSURVEYCAMPAIGN'] = "CAMPAÑA NO ENCUESTA";
$lang['go_LEADRECYCLES'] = "RECICLA PLOMO";
$lang['go_LEADRECYCLESTATUS'] = "ESTADO DE RECICLAJE DE PLOMO";
$lang['go_MODIFYCAMPAIGNLEADRECYCLING'] = "MODIFICAR LA CAMPAÑA DE RECICLAJE DE PLOMO";
$lang['go_DELETECAMPAIGNLEADRECYCLING'] = "ELIMINAR LA CAMPAÑA DE RECICLAJE DE PLOMO";

$lang['go_CURRENTHOPPERLIST_'] = "LISTA DEL PAGADOR ACTUAL:";
$lang['go_TotalLeadsinHopper_'] = "Potenciales totales en Hopper:";

$lang['go_PleasecheckthedatayouenteredontheCampaignName'] = "Por favor, compruebe los datos introducidos en el Nombre de la campaña. Tiene que ser por lo menos 6 caracteres de longitud.";

//go_campaign_wizard.php
$lang['go_CampaignWizard'] = "Asistente de Campaña";
$lang['go_Outbound'] = "Outbound";
$lang['go_CampaignType_'] = "Tipo de Campaña:";
//[go_CampaignID_]
$lang['go_Inbound'] = "Entrante";
$lang['go_Blended'] = "Mezclado";
$lang['go_Survey'] = "Estudio";
$lang['go_CopyCampaign'] = "Campaña Copiar";
$lang['go_checktoeditcampaignidandname'] = "comprobar editar Identificación campaña y nombre";
//[go_CampaignName_]
$lang['go_DIDTFN_Extension_'] = "DID / Extensión TFN:";
$lang['go_acceptsonlynumbers'] = "acepta sólo números";
$lang['go_CallRoute'] = "Llame a la ruta:";
$lang['go_INGROUPcampaign'] = "ENDOGRUPO (campaña)";
$lang['go_IVRcallmenu'] = "IVR (menú de llamada)";
$lang['go_AGENT'] = "AGENTE";
$lang['go_VOICEMAIL'] = "CORREO DE VOZ";
$lang['go_CopyFrom_'] = "Copia De:";
$lang['go_SurveyType_'] = "Tipo de levantamiento:";
$lang['go_VOICEBROADCAST'] = "VOZ BROADCAST";
$lang['go_SURVEYPRESS1'] = "ENCUESTA PULSE 1";
$lang['go_NumberofChannels_'] = "Número de Canales:";
$lang['go_Agent_'] = "Agente";
//[go_NONE]
$lang['go_Email_'] = "Email:";
$lang['go_GroupColor_'] = "Grupo de Color:";
$lang['go_false'] = "falso";
$lang['go_Back'] = "Espalda";
$lang['go_Next'] = "Siguiente";
$lang['go_Modify'] = "Modificar";
$lang['go_PleaseinputtheyourDIDTFNExtension'] = "Por favor introduce el su DID / Extensión TFN.";
$lang['go_Shouldnotbeempty'] = "No debería estar vacío.";
$lang['go_DIDTFNNotAvailable'] = "DID / TFN no disponible.";
$lang['go_CampaignNameatleast6characters'] = "Nombre de la campaña debe ser de al menos 6 caracteres de longitud.";
$lang['go_CampaignID3to8characters'] = "ID de campaña debe estar entre 3 y 8 caracteres.";
$lang['go_CampaignNameshouldnotbeempty'] = "Nombre de la campaña no debe estar vacío.";


//go_lead_filters.php
//[go_PleasecomposeanSQLquery]
//$lang['go_UserGroup_'] = "";
//$lang['go_Fields_'] = "";
//$lang['go_FilterOptions_'] = "";
//$lang['go_FilterOptions_'] = "";
//[go_FilterbyDate]
//[go_FilterbyCalledCount_]
//[FilterbyCountryCode]
//[FilterbyAreaCode]
$lang['go_FilterbyTimezone'] = "";
//[FilterbyState]


//go_campaign_wizard_output.php
$lang['go_Pleaseincludealeadfile'] = "Por favor, incluya un archivo de plomo";
$lang['go_Uploadedfileisinvalid'] = "El archivo subido no es válido";
$lang['go_FilemustbeinExcelformatxlsxlsxorinCommaSeparatedValuescsv'] = "El archivo debe estar en formato Excel (xls, xlsx) o en valores separados por comas (CSV).";
$lang['go_PleaseincludeaWAVfile'] = "Por favor, incluya un archivo WAV.";
$lang['go_Erroruploading'] = "Error al subir";
$lang['go_Pleaseuploadonlyaudiofiles'] = "Por favor, sube sólo los archivos de audio";
$lang['go_Pleaseuploadwavfile16bitMono8kPCMWAVaudiofilesonly'] = "Por favor, sube .wav archivo (16bit Mono 8k PCM WAV sólo los archivos de audio)";
$lang['go_More'] = "Más";

$lang['go_RecordingsforthisLeadID'] = "Grabaciones para este ID de plomo";
$lang['go_SearchDNCNumbers'] = "Buscar números DNC";

$lang['go_Skip'] = "Omitir";
$lang['go_SaveandFinish'] = "Guardar y Terminar";

$lang['go_Westronglyrecommend'] = "Recomendamos encarecidamente";
$lang['go_files'] = "Archivos";
$lang['go_Pleaseselectfieldvalues'] = "Por favor, seleccione los valores de campo.";
$lang['go_PleasefillinatleastPHONENUMBERFIRSTNAMEandLASTNAME'] = "Por favor rellenar al menos número de teléfono, nombre y apellido.";
$lang['go_LeadFile_'] = "Archivo de Ejecución:";
$lang['go_ListID_'] = "List ID:";
$lang['go_Country_'] = "País:";
$lang['go_CheckforDuplicates'] = "Compruebe si hay Duplicados:";
$lang['go_NODUPLICATECHECK']  = "NO DUPLICATE CHEQUE";
$lang['go_CHECKFORDUPLICATESBYPHONEINLISTID'] = "COMPROBAR SI HAY DUPLICADOS POR TELÉFONO EN LISTA ID";
$lang['go_CHECKFORDUPLICATESBYPHONEINALLCAMPAIGNLISTS'] = "COMPROBAR SI HAY DUPLICADOS POR TELÉFONO EN TODAS LAS LISTAS DE CAMPAÑA";
$lang['go_CHECKFORDUPLICATESBYPHONEINENTIRESYSTEM'] = "COMPROBAR SI HAY DUPLICADOS POR TELÉFONO EN SISTEMA ENTERO";
$lang['go_CHECKFORDUPLICATESBYALTPHONEINLISTID']  = "COMPROBAR SI HAY DUPLICADOS DE ALT-TELÉFONO EN LISTA ID";
$lang['go_CHECKFORDUPLICATESBYALTPHONEINENTIRESYSTEM'] = "COMPROBAR SI HAY DUPLICADOS DE ALT-TELÉFONO EN SISTEMA ENTERO";
$lang['go_TimeZoneLookup'] = "Tiempo de zona de búsqueda:";
$lang['go_COUNTRYCODEANDAREACODEONLY'] = "CÓDIGO DE PAÍS Y CÓDIGO DE ÁREA ÚNICA";
$lang['go_POSTALCODEFIRST'] = "CÓDIGO POSTAL PRIMER";
$lang['go_OWNERTIMEZONECODEFIRST'] = "PROPIETARIO HUSO HORARIO CÓDIGO PRIMERO";
$lang['go_UPLOADLEADS'] = "CABLES DE CARGAR";
$lang['go_OKTOPROCESS'] = "OK PARA PROCESAR";


//go_campaign_wizard_fields.php
$lang['go_Processing'] = "Tratamiento";
$lang['go_LISTIDFORTHISFILE'] = "ID LISTA PARA ESTE ARCHIVO";
$lang['go_COUNTRYCODEFORTHISFILE'] = "CÓDIGO PAÍS PARA ESTE ARCHIVO";
$lang['go_LeadFileSuccessfullyLoaded'] = "Archivo liderar con éxito Loaded";
$lang['go_Currentfilestatus'] = "Estado del archivo actual";
$lang['go_Good_'] = "Bueno:";
$lang['go_Bad_'] = "Malo:";
$lang['go_Total_'] = "Total:";
$lang['go_Duplicate_'] = "Duplicar:";
$lang['go_PostalMatch_'] = "Partido Postal:";
$lang['go_Checkthisboxifyouwanttoshowtheresult'] = "Marque esta casilla si desea mostrar el resultado";


//go_campaign_statuses.php
$lang['go_Pleasefillinthefollowing'] = "Por favor, rellene el siguiente:";
$lang['go_Thereisalreadyaglobalstatusinthesystemwiththisname_'] = "Ya existe un estado global en el sistema con este nombre:";
$lang['go_Thereisalreadyacampaignstatusinthesystemwiththisname_'] = "Ya existe una campaña de un estado en el sistema con este nombre:";
$lang['go_CUSTOMSTATUSESWITHINTHISCAMPAIGN'] = "ESTADOS PERSONALIZADOS DENTRO DE ESTA CAMPAÑA:";
$lang['go_CATEGORY'] = "CATEGORÍA";
$lang['go_MODIFYSTATUS_'] = "MODIFICAR EL ESTADO";
$lang['go_SELECTABLE_'] = "SELECCIONABLE";
$lang['go_HUMANANSWERED_'] = "HUMANO CONTESTADO";
$lang['go_SCHEDULEDCALLBACK'] = "RETROLLAMADA PROGRAMADO";
$lang['go_UNWORKABLE'] = "IMPRACTICABLE";
$lang['go_NOTINTERESTED'] = "NO HAY INTERESADO";
$lang['go_CUSTOMERCONTACT'] = "CLIENTE CONTACTO";
$lang['go_UNDEFINED'] = "INDEFINIDO";
$lang['go_SALE'] = "VENTA";
$lang['go_LEADRECYCLINGWITHINTHISCAMPAIGN'] = "RECICLAJE DE PLOMO EN ESTA CAMPAÑA";
$lang['go_ATTEMPTDELAY'] = "RETRASO INTENTO";
$lang['go_MAXIMUMATTEMPTS'] = "INTENTOS MÁXIMOS";
$lang['go_LEADSATLIMIT'] = "LLEVA AL LÍMITE";
$lang['go_PAUSECODESWITHINTHISCAMPAIGN'] = "CÓDIGOS DE PAUSA EN ESTA CAMPAÑA";
$lang['go_PAUSECODENAME'] = "PAUSA CÓDIGO NOMBRE";
$lang['go_PAUSECODESTATUS'] = "CÓDIGO PAUSA ESTADO";
$lang['go_BILLABLE'] = "FACTURABLE";
$lang['go_HALF'] = "MEDIA";
$lang['go_HOTKEYSWITHINTHISCAMPAIGN'] = "HOTKEYS DENTRO DE ESTA CAMPAÑA";
$lang['go_HOTKEY'] = "HOTKEY";
$lang['go_HOTKEYSTATUS'] = "ESTADO HOTKEY";
$lang['go_HOTKEYS'] = "HOTKEYS";
$lang['go_HotKeys'] = "HotKeys";
$lang['go_HotKeysTab'] = "<b> Teclas de acceso rápido Tab </b> - Permite administrador para configurar una tecla numérica en el teclado como una tecla de acceso directo a la disposición automáticamente una llamada.";

$lang['go_LeadFilters'] = "Filtros de plomo";
$lang['go_LeadFiltersTab'] = "<b> El plomo Filtros Tab </b> - Este es un método de filtrado de sus clientes potenciales utilizando un fragmento de una consulta SQL. Utilice esta función con <br/> precaución, es fácil dejar de marcar accidentalmente con la menor alteración en la sentencia SQL. Predeterminado es Ninguno.";
$lang['go_RealtimeMonitoring'] = "Monitoreo en tiempo real";
$lang['go_Monitor'] = "Monitor";
$lang['go_AdminPhone'] = "Admin Teléfono";
$lang['go_showList'] = "showList";


$lang['go_add_new_ga'] = "Add new Group Access";
//delete campaign
$lang['go_DelCamp_Confirmation']  = "Seguro que quieres eliminar esta Campaña?";
$lang['go_DelCamp_Notification']  = "Por favor, asegúrese de transferir ningún código de lista existentes que tienen cables subidos a ella para cualquier campaña disponibles.";
//delete disposition
$lang['go_DelDispo_Confirmation'] = "Are you sure you want to delete the selected campaign's statuses?";
$lang['go_BLINDMONITORING'] = "MONITOREO DE CIEGOS";

//DISPOSITION TAB
$lang['go_Dispositions']         = "Disposiciones";
$lang['go_Disposition'] = "Disposición";
//-r camp id
//-r camp name
$lang['go_CUSTOMDISPOSITION']    = "DISPOSICIÓN PERSONALIZADA";
//-r dispalying

//SW- status wizard
//CNS- create new status
$lang['go_SWCNS']               = "Asistente de Estado » Crear nuevo Estado";
$lang['go_CLOSE']		= "CERCA";
//-r step1
$lang['go_Campaign_']		= "Campaña:";
$lang['go_CampaignTooltip']	= "<b> Campaña </b> - especifica la campaña que va a utilizar la tecla de acceso directo.";
$lang['go_ALLCAMPAIGN']         = "---  TODA LA CAMPAÑA  ---";
$lang['go_Status_']             = "Estado:";
$lang['go_StatusTooltip'] 	= "<b> Estado </b> - establece la disposición que se asignará a la llamada cuando se pulsa la tecla de acceso directo";
$lang['go_egNEW']               = "Por ejemplo. Nuevo";
$lang['go_StatusName_']         = "Estado Nombre:";
$lang['go_ADDSTATUS']	 	= "AÑADIR ESTADO";
$lang['go_egNCS']               = "Por ejemplo. Nuevo Estado de la campaña";
$lang['go_Selectable_']         = "Seleccionable:";
$lang['go_HumanAnswered_']      = "Humano Respondido:";
$lang['go_Sale_']               = "Venta:";
$lang['go_DNC_']                = "DNC (No llame):";
$lang['go_DNCfile']		= "DNC";
$lang['go_CustomerContact_']    = "Cliente Contacto:";
$lang['go_NotInterested_']      = "No hay interés:";
$lang['go_Unworkable_']         = "Impracticable:";
$lang['go_ScheduledCallback_']  = "Devolución de llamada programada:";

$lang['go_YES']			= "SÍ";
$lang['go_NO']			= "NO";
$lang['go_Submit']              = "Presentar";
$lang['go_Yes']			= "Sí";
$lang['go_Later']		= "Más tarde";

//tooltip
$lang['go_DispositionTab']               	 = "<b>Disposiciones Tab</b> - Proporciona una lista de disposiciones personalizados creados en la cuenta y le permite crear otros nuevos.";
$lang['go_MODIFYCAMPAIGNSTATUSES']               = "MODIFICAR ESTADOS DE CAMPAÑA";
$lang['go_MODIFYCAMPAIGNSTATUS']		 = "MODIFICAR EL ESTADO DEL CAMPAÑA";
$lang['go_DELETECAMPAIGNSTATUSES']               = "ELIMINAR LOS ESTADOS DE CAMPAÑA";
$lang['go_DELETECAMPAIGNSTATUS']		 = "BORRAR STATUS CAMPAÑA";
$lang['go_VIEWDISPOSITIONFORCAMP']               = "VER DISPOSICIONES PARA LA CAMPAÑA";
$Lang['go_MODIFYLISTID'] 			 = "MODIFICAR LISTA ID";
$lang['go_ModifyListID']			 = "Modificar Lista I.D.";
$lang['go_CampaignRecordingTooltip'] 		 = "<center> <b> Grabación Campaña </b> </center> </br> <b> Desactivado </b> -. se registraron llamadas </br> <b> En </b> - All <b> saliente se registrarán </b> llamadas </br> <b> ONDEMAND </b> -. No <b> saliente se registrarán </b> llamadas </br> a menos que el agente hace clic en el botón de grabación en el Agente </br> página web";

$lang['go_Youdonthavepermissiontodeletethisrecords']="Usted don \ 't tiene permiso para eliminar este registro(s)";
$lang['go_successfullydeleted'] = "eliminado correctamente";

//PAUSE CODE TAB
$lang['go_PauseCode'] 		       = "Códigos de Pausa";
$lang['go_PauseCode_']		       = "Códigos de Pausa:";
$lang['go_PauseCodeTooltip']	       = "<b> Códigos Pausa Tab </b> - Permite administrador para establecer códigos de pausa que requerirán agentes haciendo clic en el botón de pausa para especificar el motivo de la pausa.";
$lang['go_PauseCodeAlertGofree']       = "Esta función no está disponible en Go gratuito . Por favor, actualice su suscripción.";
$lang['go_PauseCodeTooltip2']	       = "<b> Pausa Código </b> - establece el código que aparecerá cuando se genera un informe de llamadas.";
$lang['go_PauseCodeName_']	       = "Pausa Code Name:";	
$lang['go_PauseCodeName_Tooltip']      = "<b> Pausa Código Nombre </b> - da una descripción del código de pausa.";
$lang['go_Billable_'] 	       	       = "Facturable:";
$lang['go_BillableTooltip']	       = "<b> Facturable </b> - se utiliza para la generación de informes para ayudar en la computación de tiempo facturable de un agente.";

$lang['go_ActivateSelected']         = "Activar Seleccionada";
$lang['go_DeactivatedSelected']      = "Desactivar Seleccionado";
$lang['go_DeleteSelected']           = "Eliminar Seleccionados";

//Dashboard - Walk Through
$lang['goDashboard_welcomeToGOadmin'] = "Bienvenidos a GOadministración!";
$lang['goDashboard_thisWalkThroughWill'] = "Este paseo por le ayudará a navegar el sistema fácilmente o";
$lang['goDashboard_skip'] = "Omitir";
$lang['goDashboard_thisWalkThrough'] = "este paseo por.";

$lang['goDashboard_appMenu'] = "Menú de la aplicación";
$lang['goDashboard_hoverMouse'] = "Pase el ratón para ver los detalles";

$lang['goDashboard_loadCredits'] = "De crédito(s) de carga";
$lang['goDashboard_click'] = "Haga clic";
$lang['goDashboard_here'] = "aquí";
$lang['goDashboard_forHowToLoadCredit'] = "para saber cómo cargar crédito";

$lang['goDashboard_monitorBarge'] = "Monitor/Barcaza";
//$lang['goDashboard_click'] = "";
//$lang['goDashboard_here'] = "";
$lang['goDashboard_toMonitor'] = "para supervisar o agente(s) en vivo barcaza";

$lang['goDashboard_activeCalls'] = "Llamadas activas";
$lang['goDashboard_clickToShowActiveCalls'] = "Haga clic para mostrar las llamadas activas de ser colocado";

$lang['goDashboard_thatsIt'] = "Eso es todo!";
$lang['goDashboard_toGetStarted'] = "Para empezar lo antes posible por favor vaya sobre nuestros tutoriales aquí:";
$lang['goDashboard_tutorials'] = "Tutoriales";
$lang['goDashboard_showThisIntro'] = "Mostrar esta introducción ayuda de nuevo el próximo inicio de sesión?";
$lang['goDashboard_next'] = "Siguiente";
$lang['goDashboard_close'] = "Cerca";


//In-Groups
$lang['go_Inbound']= "Entrante";
$lang['go_AddNewIngroup'] = "Agregar Nuevo Ingroup";
$lang['go_AddNewDID'] = "Agregar Nuevo DID";
$lang['go_AddNewCallMenu'] = "Añadir menú Nueva llamada";
$lang['go_DESCRIPTIONS'] = "DESCRIPCIONES";
$lang['go_PRIORITY'] = "PRIORIDAD";
$lang['go_MODIFYINGROUP'] = "MODIFICAR EN-GRUPO";
$lang['go_CannotdeleteAGENTDIRECT'] = "No se puede eliminar AGENTDIRECT";
$lang['go_DELETEINGROUP'] = "ELIMINAR EN-GRUPO";
$lang['go_VIEWINFOFORINGROUP'] = "INFO VISTA EN EL GRUPO";
$lang['go_Settings'] = "Ajustes";
$lang['go_Color'] = "Color";
$lang['go_NextAgentCall'] = "Siguiente Agente Call";
$lang['go_QueuePriority'] = "Cola de Prioridad";
$lang['go_FronterDisplay'] = "Pantalla Fronter";
$lang['go_ADVANCESETTINGS'] = "CONFIGURACION AVANZADA";

$lang['go_OnHookRingTime'] = "Colgado duración del Timbre";
$lang['go_IgnoreListScriptOverride'] = "No haga caso de anulación de la lista de secuencias de comandos";
$lang['go_GetCallLaunch'] = "Obtener Lanzamiento Call";
$lang['go_TransferConfDTMF'] = "Transferencia-conf DTMF";
$lang['go_TransferConfNumber'] = "transferir-Conf Número";
$lang['go_TimerAction'] = "temporizador Acción";
$lang['go_TimerActionMessage'] = "Temporizador de mensaje de acción";
$lang['go_TimerActionSeconds'] = "Segundos Temporizador Acción";
$lang['go_TimerActionDestination'] = "Temporizador Acción Destino";
$lang['go_DropCallSeconds'] = "Caiga segundos de llamada";
$lang['go_DropAction'] = "Acción Gota";
$lang['go_DropExten'] = "Gota Exten";

$lang['go_Voicemail']  = "Correo de voz";
$lang['go_VoicemailChooser'] = "Voicemail Selector";
$lang['go_DropTransferGroup']  = "Gota Grupo de Transferencia";
$lang['go_DropCallMenu'] = "Menú de llamada perdida";
$lang['go_CallTime'] = "Duración de la llamada";
$lang['go_AfterHoursAction'] = "After Hours Acción";
$lang['go_AfterHoursMessageFilename'] = "Después de horas de mensajes Nombre de archivo";

$lang['go_AfterHoursExtension'] = "Después de horas de Extensión";
$lang['go_AfterHoursVoicemail'] = "Después de horas de correo de voz";
$lang['go_AfterHoursTransferGroup'] = "Después Grupo Horas Transferencia";
$lang['go_NoAgentsNoQueueing'] = "No Agentes No Queueing";
$lang['go_NoAgentNoQueueAction'] = "Ninguna acción agente No Queue";
$lang['go_CallMenu'] = "menú de llamada";
$lang['go_InGroup'] = "En-Group";
$lang['go_HandleMethod'] = "Maneje Método";
$lang['go_SearchMethod'] = "Método de búsqueda";
$lang['go_Value'] = "Valor";
$lang['go_Extension'] = "Extensión";

$lang['go_Context'] = "Contexto";
$lang['go_VoicemailBox'] = "Buzón de voz";
$lang['go_MaxCallsMethod'] = "Max llamadas Método";
$lang['go_MaxCallsCount'] = "Max llamadas Conde";
$lang['go_MaxCallsAction'] = "Llamadas Max Acción";
$lang['go_WelcomeMessageFilename'] = "Mensaje de Bienvenida Nombre";
$lang['go_PlayWelcomeMessage'] = "Juega Mensaje de Bienvenida";
$lang['go_MusicOnHoldContext'] = "Música en espera Contexto";
$lang['go_OnHoldPromptFilename'] = "On Hold Nombre Prompt";
$lang['go_OnHoldPromptInterval'] = "En espera de intervalo Prompt";
$lang['go_OnHoldPromptNoBlock'] = "On Hold Prompt No Bloquear";
$lang['go_OnHoldPromptSeconds'] = "On Hold segundos Prompt";

$lang['go_PlayPlaceinLine'] = "Juega Place en Línea";
$lang['go_PlayEstimatedHoldTime'] = "Jugar Hold Time Estimado";
$lang['go_CalculateEstimatedHoldSeconds'] = "Calcular Segundos Estimados Hold";
$lang['go_EstimatedHoldTimeMinimumFilename'] = "Hold Tiempo estimado Nombre mínima";
$lang['go_EstimatedHoldTimeMinimumPromptNoBlock'] = "Hold Time estimado mínimo Prompt No Bloquear";
$lang['go_EstimatedHoldTimeMinimumPromptSeconds'] = "Tiempo estimado Hold segundos Prompt mínimos";

$lang['go_WaitTimeOption'] = "Espere opción De Tiempo";
$lang['go_WaitTimeSecondOption'] = "Tiempo de Espera de Segunda Opción";
$lang['go_WaitTimeThirdOption'] = "Tiempo de espera Tercera Opción";
$lang['go_WaitTimeOptionSeconds'] = "Tiempo de espera segundos Opción";
$lang['go_WaitTimeOptionExtension'] = "Tiempo de espera de Extensión Opción";
$lang['go_WaitTimeOptionCallmenu'] = "Tiempo de espera Opción Callmenu";
$lang['go_WaitTimeOptionVoicemail'] = "Tiempo de espera Opción de correo de voz";

$lang['go_WaitTimeOptionPressFilename'] = "Tiempo de espera Opción Pulse Nombre del archivo";
$lang['go_WaitTimeOptionPressNoBlock'] = "Tiempo de espera Opción Pulse No Bloquear";
$lang['go_WaitTimeOptionPressFilenameSeconds'] = "Tiempo de espera oprima la opción segundos Nombre de archivo";
$lang['go_WaitTimeOptionAfterPressFilename'] = "Espere opción de tiempo Después de Prensa Nombre";
$lang['go_WaitTimeOptionCallbackListID'] = "Tiempo de espera Opción de devolución de llamada Lista ID";
$lang['go_WaitHoldOptionPriority'] = "Espere Hold Prioridad Opción";

$lang['go_EstimatedHoldTimeOption'] = "Opción Hold Time estimado";
$lang['go_HoldTimeSecondOption'] = "Tiempo de Espera segunda opción";
$lang['go_HoldTimeThirdOption'] = "Sostenga Tiempo Tercera Opción";
$lang['go_HoldTimeOptionSeconds'] = "Hold Time segundos Opción";
$lang['go_HoldTimeOptionMinimum'] = "Mantenga Opción Tiempo mínimo";
$lang['go_HoldTimeOptionExtension'] = "Sostenga Tiempo Extensión Opción";
$lang['go_HoldTimeOptionCallmenu'] = "Sostenga Tiempo Opción Callmenu";

$lang['go_HoldTimeOptionVoicemail'] = "Sostenga Tiempo Opción de correo de voz";
$lang['go_HoldTimeOptionTransferInGroup'] = "Sostenga Tiempo de transferencia Opción A-Group";
$lang['go_HoldTimeOptionPressFilename'] = "Mantenga Opción hora Pulse Nombre del archivo";
$lang['go_HoldTimeOptionPressNoBlock'] = "Mantenga opción de tiempo Pulse No Bloquear";
$lang['go_HoldTimeOptionPressFilenameSeconds'] = "Hold Time Opción Pulse segundos Nombre de archivo";
$lang['go_HoldTimeOptionAfterPressFilename'] = "Mantenga opción de tiempo Después de Prensa Nombre";
$lang['go_HoldTimeOptionCallbackListID'] = "Sostenga Tiempo Opción de devolución de llamada Lista ID";
$lang['go_AgentAlertFilename'] = "Alerta Agente Nombre";
$lang['go_AgentAlertDelay'] = "Alerta Agente Delay";
$lang['go_DefaultTransferInGroup'] = "Por defecto Transfer In-Group";

$lang['go_DefaultGroupAlias'] = "Por defecto Grupo Alias";
$lang['go_DialInGroupCID'] = "Dial In-Grupo CID";
$lang['go_HoldRecallTransferInGroup'] = "Transferencia de Recuperación en Retención En-Group";
$lang['go_NoDelayCallRoute'] = "Sin retardo Ruta de llamada";
$lang['go_InGroupRecordingOverride'] = "En-Grupo de anulación de grabación";
$lang['go_InGroupRecordingFilename'] = "En-Recording Group Nombre";
$lang['go_StatsPercentofCallsAnsweredWithinXseconds'] = "Estadísticas Porcentaje de llamadas contestadas dentro de X segundos";
$lang['go_StartCallURL'] = "Iniciar llamada URL";

$lang['go_DispoCallURL'] = "Dispo URL Llamada";
$lang['go_AddLeadURL'] = "Añadir URL Plomo";
$lang['go_NoAgentCallURL'] = "No URL Agente Call";
$lang['go_ExtensionAppendCID'] = "Extensión Append CID";
$lang['go_UniqueidStatusDisplay'] = "Estado uniqueid Display";
$lang['go_UniqueidStatusPrefix'] = "Prefijo Estado uniqueid";
$lang['go_USER'] = "USUARIO";
$lang['go_TENANTID'] = "INQUILINO ID";
$lang['go_CALLSTODAY'] = "LLAMADAS DE HOY";
$lang['go_GRADE'] = "GRADO";
$lang['go_RANK'] = "RANGO";
$lang['go_SELECTED'] = "SELECCIONADO";

$lang['go_InGroupWizard'] = "En-Group Asistente";
$lang['go_CreateNewInGroup'] = "Crear nuevo En-Group";
$lang['go_GroupID'] = "ID de grupo";
$lang['go_nospaces2and20charactersinlength'] = "* (sin espacios). 2 y 20 caracteres de longitud";
$lang['go_2and20charactersinlength'] = "* 2 y 20 caracteres de longitud";
$lang['go_GroupColor'] = "Grupo de color";
$lang['go_UserGroup'] = "Grupo de Usuarios";
$lang['go_FronterDisplay'] = "Pantalla Fronter";

//In-Group -> Phone Numbers (DIDs/TFNs) Tab
$lang['goInbound_phoneNumbersTab']= "Números de Teléfono (DIDs/TFNs)";
$lang['goInbound_phoneNumbers']= "NÚMEROS DE TELÉFONO";
$lang['goInbound_description']= "DESCRIPCIÓN";
$lang['goInbound_status']= "ESTADO";
$lang['goInbound_route']= "RUTA";
$lang['goInbound_action']= "ACCIÓN";
$lang['goInbound_actionActivateSelected']= "Activar Seleccionada";
$lang['goInbound_actionDeactivateSelected']= "Desactivar Seleccionado";
$lang['goInbound_actionDeleteSelected']= "Eliminar Seleccionados";
$lang['goInbound_displaying']= "Viendo del";
$lang['goInbound_to']= "al";
$lang['goInbound_of']= "de";
$lang['goInbound_ingroups']= "en-grupos";
$lang['goInbound_addNewDID']= "Añadir nuevo DID";
$lang['goInbound_didWizard']= "¿FUE EL Mago » Crear nuevo DID";
$lang['goInbound_didExtension']= "DID Extensión";
$lang['goInbound_didDescription']= "DID Descripción";
$lang['goInbound_active']= "Activo";
$lang['goInbound_didRoute']= "DID Ruta";
$lang['goInbound_userGroups']= "Grupo de Usuarios";
$lang['goInbound_agentId']= "Identificación del agente";
$lang['goInbound_agentUnavailableAction']= "Agente No Disponible Acción";
$lang['goInbound_submit']= "Presentar";
$lang['goInbound_agentRouteSettings']= "Configuración de ruta Agente En-Group";
$lang['goInbound_cleanCIDNumber']= "Clean Número CID";
$lang['goInbound_filterInboundNumber']= "Filtra Número entrante";
$lang['goInbound_advanceSettings']= "AVANCE AJUSTES";
$lang['goInbound_saveSettings']= "GUARDAR LOS AJUSTES";
$lang['goInbound_modifyDIDRecord']= "Modificar DID Récord";
$lang['goInbound_success']= "ÉXITO: DID modificado";
$lang['goInbound_deleteConfirmationMsg']= "¿Seguro que quieres borrar este DID";
$lang['goInbound_modifyDID']= "Modificar DID";
$lang['goInbound_deleteDID']= "Eliminar DID";
$lang['goInbound_searchDIDs']= "Buscar DIDs";






################## Jeremiah Sebastian Samatra #####################



//START OF REPORTS

######################go_reports.php###################
//Pagetitle
$lang['go_statistical_rep'] 	      = "Informe Estadístico"; 
$lang['go_agent_stats_rep'] 	      = "Estadísticas agente de informes"; 
$lang['go_dial_statuses_summary_rep'] = "Marque Los estados Informe resumido"; 
$lang['go_sales_per_agent_rep']       = "Ventas por informe Agente"; 
$lang['go_oi_sales_tracker'] 	      = "Saliente / Entrante Ventas Rastreador Reportar"; 
$lang['go_inbound_rep'] 	      = "Informe Inbound"; 
$lang['go_export_call_rep'] 	      = "Exportar informe de llamadas"; 
$lang['go_dashboard'] 		      = "Salpicadero"; 
$lang['gocdr'] = "CDR";
//bannertitle
$lang['go_reports_analytics'] 	      = "Informes y Analytics"; 
$lang['go_reports_analytics_tooltip'] = "Informes y Analytics - le dará prácticamente todos los datos que necesita sobre su cuenta. Los informes se pueden descargar y en formato de hoja de cálculo. Hay una amplia variedad de informa puedes elegir con cada uno informes personalizables de adaptar a sus necesidades. También se mostrará en pantalla un gráfico comparando diferentes datos en relación con los demás. Cada tipo de informe será discutido en detalle en las páginas siguientes.";
$lang['go_custome_tabs'] = "Fichas personalizadas permiten diferentes tipos de informes que se mostrará en la pantalla."; //"Custom Tabs allow for different <br>types of reports to be displayed on <br>the screen";
$lang['go_daily'] = "Diario"; //"Daily";
$lang['go_weekly'] = "Semanal"; //"Weekly";
$lang['go_monthly'] = "Mensual"; //"Monthly";
$lang['go_calendar_tooltip'] = "El icono de Calendario le permite a generar un informe basado en un rango de fechas específico."; //"The Calendar icon allows you to<br>generate a report based on a specific<br>date range.";
$lang['go_calendar_icon'] = "El icono de Calendario le permite a generar un informe basado en un rango de fechas específico."; //"The Calendar icon allows you to<br>generate a report based on a specific<br>date range.";
$lang['go_sel_date_range'] = "Seleccione rango de fechas"; //"Select date range";
$lang['go_sel_camp'] = "Seleccione una Campaña"; //"Select a Campaign";
$lang['go_statistical_rep_tooltip'] = "Informe estadístico - genera una representación gráfica de los datos de un determinado campaña. Los datos incluirán el total de llamadas y sus disposiciones y las llamadas promedio en un diaria, semanal o mensual."; /*"Statistical Report -- generates a graphical representation of data on a specific
<br>campaign. Data will include total calls and their dispositions and the average calls on a
<br>daily, weekly or monthly basis.";*/
$lang['go_agent_time_details_tooltip'] = "Agente Detalles de tiempo - se presenta un desglose de toda la actividad del agente hizo durante su turno."; //"Agent Time Details -- provides a breakdown on <br/>all activity the agent did during his shift.";
$lang['go_agent_time_detail'] = "Agente Detalles de tiempo"; //"Agent Time Detail";
$lang['go_agent_performance_detail_tooltip'] = "Detalle Performance Agent - da un informe detallado sobre cada actividad agent.s para una específica campaña en un período de tiempo especificado. El informe desglosa cada actividad agent.s durante su turno. El informe se divide para el número total de llamadas, Tiempo de pausa, tiempo de espera, tiempo de conversación, tiempo de disposicion una llamada, y el tiempo de Baja temporal. El informe también se dará información sobre las disposiciones y su total."; /*"Agent Performance Detail -- gives a detailed report on each agent.s activity for a specific
<br/>campaign on a specified time period. The report breaks down each agent.s activity during his shift.
<br/>The report is broken down to the total number of calls, Pause time, Wait time, Talk time, Time to
<br/>disposition a call, and Wrap-up time. The report will also give information on the dispositions and
<br/>their total.";*/
$lang['go_agent_performance_detail'] = "Detalle Performance Agent"; //"Agent Performance Detail";
$lang['go_dial_statuses_summary_tooltip'] = "Marque Los estados Resumen - mostrará el número de llamadas que han sido dispositioned para cada llamada a un ventaja específica. Esta página se mostrará disposiciones en un plomo para la llamada inicial, así como las llamadas siguientes hasta que plomo"; /*"Dial Statuses Summary -- will display the number of
<br/>calls that have been dispositioned for each call to a
<br/>specific lead. This page will display dispositions on a
<br/>lead for the initial call, as well as succeeding calls to that
<br/>lead.";*/
$lang['go_dial_statuses_summary'] = "Marque Los estados Resumen"; //"Dial Statuses Summary";
$lang['go_sales_per_agent_tooltip'] = "Ventas por agente - se mostrarán las ventas totales de cada agente en un campaña específica en un rango de fechas determinado. Las ventas son rastreados si fueron hechas durante una llamada saliente o un llamada entrante."; /*"Sales Per Agent -- will display the total sales of each agent on a
<br/>specific campaign on a given date range. Sales are tracked
<br/>whether they were made during an outbound call or an
<br/>inbound call.";*/
$lang['go_sales_per_agent'] = "Ventas por agente"; //"Sales Per Agent";
$lang['go_sales_tracker_tooltip'] = "Rastreador de ventas - muestra toda venta realizada para una campaña específica en un determinado rango de fechas. La información mostrada incluye la fecha y hora dela llamada, el agente de identificación, nombre del agente, y el número de teléfono."; /*"Sales Tracker -- displays all sale made for a specific campaign on a
<br>given date range. Information displayed includes the date and time of
<br>the call, the agent ID, name of the agent, and the phone number.";*/
$lang['go_sales_tracker'] = "Rastreador de ventas"; //"Sales Tracker";
$lang['go_sales_tracker_tooltip'] = "Rastreador de ventas - muestra toda venta realizada para una campaña específica en un determinado rango de fechas. La información mostrada incluye la fecha y hora de la llamada, el agente de identificación, nombre del agente, y el número de teléfono."; /*"Sales Tracker -- displays all sale made for a specific campaign on a
<br>given date range. Information displayed includes the date and time of
<br>the call, the agent ID, name of the agent, and the phone number.";*/
$lang['go_inbound_call_rep_tooltip'] = "Informe de llamada entrante - visualizar todas las llamadas entrantes recibidas por un determinado endogrupo. Números de teléfono de la persona que llama, la fecha actual y hora de la llamada, duración de la la llamada y las disposiciones de las llamadas en un rango de fechas determinado están todos en la lista"; /*"Inbound Call Report -- display all inbound calls received by a specified
<br>ingroup. Phone numbers of the caller, actual date and time of call, duration of
<br>the call and the dispositions of the calls on a given date range are all listed";*/
$lang['go_inbound_call_rep'] = "Informe de llamadas entrantes."; //"Inbound Call Report";
$lang['go_export_call_rep_tooltip'] = "Exportar informe de llamadas - genera un informe sobre todos los datos y plomo información de sus llamadas. El informe se basa en la Campañas, grupos de entrada, lista de ID, estatutos, campos personalizados y rango de fechas usted seleccione. El informe generado será en formato de hoja de cálculo."; /*"Export Call Report -- generates a report on all data and lead
<br/>information of your calls. The report will be based on the
<br/>Campaigns, Inbound groups, List ID, Statuses, Custom fields and
<br/>date range you will select. The report generated will be in
<br/>spread sheet format.";*/
$lang['go_export_call_rep'] = "Exportar informe de llamadas"; //"Export Call Report";
$lang['go_dashboard_tooltip'] = "Salpicadero - da una representación gráfica de la Tasa de contacto tasa de ventas y la tarifa de transferencia de una campaña seleccionada. Estos datos se centra principalmente en lo bien que sus conductores eran lo que respecta a la Contacto y tasa de ventas. Buenas archivos plomo volverán alta Tasa de contacto y Tasa de Ventas."; /*"Dashboard -- gives a graphical representation of the Contact Rate,
<br/>Sales Rate and Transfer Rate of a selected campaign. This data
<br/>primarily focuses on how good your leads were with regards to the
<br/>Contact and Sales rate. Good lead files will return high Contact Rate
<br/>and Sales Rate.";*/
$lang['go_call_histo_tooltip'] = "Historial de llamadas - muestra todas las llamadas en un intervalo de fechas conjunto."; //"Call History -- displays all calls on a set date range.";
$lang['go_call_histo_cdr'] = "Historial de llamadas (CDRs)"; ///"Call History (CDRs)";

#######################go_export_reports.php######################

$lang['go_atdr_download']	 = "Hora_del_agente_Informe_detallado"; //"Agent_Time_Detail_Report";
$lang['go_apdr_download'] 	 = "Agente_Detalle_Rendimiento_Reportar"; //"Agent_Performance_Detail_Report";
$lang['go_dssr_download']	 = "Marque_Los_estados_Informe_resumido"; //"Dial_Statuses_Summary_Report";
$lang['go_spar_download']	 = "Ventas_por_informe_Agente"; //"Sales_Per_Agent_Report";
$lang['go_str_download']	 = "Ventas_Rastreador_Reportar"; //"Sales_Tracker_Report";
$lang['go_icr_download']	 = "Informe_de_llamadas_Entrantes"; //"Inbound_Call_Report";
$lang['go_ecr_download']	 = "Exportar_informe_de_llamadas"; //"Export_Call_Report";
$lang['go_d_download']		 = "Salpicadero"; //"Dashboard";
$lang['go_sr_download']		 = "Informe Estadístico"; //"Statistical_Report";


#######################go_reports_page.php######################

$lang['go_pls_sel_camp'] 	= "Por favor, seleccione una campaña."; //"Please select a campaign.";
$lang['go_selected'] 		= "Seleccionado"; //"Selected";
$lang['go_download'] 		= "Descargar"; //"Download";
$lang['go_connect_time'] 	= "Conectar Tiempo"; //"Connect Time";
$lang['go_country'] 		= "País"; //"Country";
$lang['go_description'] 	= "Descripción"; //"Description";
$lang['go_billed_duration'] 	= "Duración Anunciado"; //"Billed Duration";
$lang['go_cost'] 		= "Costo"; //"Cost";
$lang['go_export_csv'] 		= "Exportar a CSV"; //"Export to CSV";
$lang['go_agent_name_caps'] 	= "MAIL NOMBRE"; //"AGENT NAME";
$lang['go_camp_hours'] 		= "Horas de campaña"; //"Campaign Hours";
$lang['go_utilization'] 	= "Utilización"; //"Utilization";
$lang['go_callbacks'] 		= "Rellamadas"; //"Callbacks";
$lang['go_not_interested'] 	= "No hay interés"; //"Not Interested";
$lang['go_transfer_per_hour'] 	= "Transferencias por Hora"; //"Transfers per Hour";
$lang['go_total_transfer'] 	= "Las transferencias totales"; //"Total Transfers";
$lang['go_transfer_sales_rate'] = "Velocidad de transferencia de Ventas"; //"Transfer Sales Rate";
$lang['go_other_stats'] 	= "Otras estadísticas"; //"Other Stats";
$lang['go_sales_per_hour'] 	= "Las ventas por horas"; //"Sales per Hour";
$lang['go_total_sales'] 	= "Ventas totales"; //"Total Sales";
$lang['go_sales_rate'] 		= "Tasa de Ventas"; //"Sales Rate";
$lang['go_total_contacts'] 	= "Contactos totales"; //"Total Contacts";
$lang['go_contact_rate'] 	= "Tasa de Contacto"; //"Contact Rate";
$lang['go_sub_total'] 		= "Total parcial"; //"Sub Total";
$lang['go_dispo_code'] 		= "Código de disposición"; //"Dispo Code";
$lang['go_dispo_name'] 		= "Nombre disposición"; //"Dispo Name";
$lang['go_count'] 		= "Contar"; //"Count";
$lang['go_dialer_calls_caps'] 	= "LLAMADAS MARCADOR"; //"DIALER CALLS";
$lang['go_all'] 		= "TODO"; //"ALL";
$lang['go_statuses'] 		= "Los estados"; //"Statuses";
$lang['go_lists'] 		= "Listas"; //"Lists:";
$lang['go_none'] 		= "NINGUNO"; //"NONE";
$lang['go_inbound_groups'] 	= "Grupos entrantes"; //"Inbound Groups";
$lang['go_campaigns'] 		= "Campañas"; //"Campaigns";
$lang['go_campaign'] 		= "CAMPAÑA"; //"CAMPAIGN";
$lang['go_standard_caps'] 	= "ESTANDAR"; //"STANDARD";
$lang['go_extended'] 		= "EXTENDIDO"; //"EXTENDED";
$lang['go_no'] 			= "NO"; //"NO";
$lang['go_yes'] 		= "SÍ"; //"YES";
$lang['go_per_call_notes'] 	= "Per notas de la llamada"; //"Per Call Notes";
$lang['go_custome_fields'] 	= "Los campos personalizados"; //"Custom Fields";
$lang['go_location'] 		= "UBICACIÓN"; //"LOCATION";
$lang['go_filename'] 		= "NOMBRE DEL ARCHIVO"; //"FILENAME";
$lang['go_id']			= "Identificación"; //"ID";
$lang['go_recording_fields'] 	= "Campos de grabación"; //"Recording Fields";
$lang['go_exports_calls_report'] = "Llamadas Exportar informe"; //"Export Calls Report";
$lang['go_no_records_found'] 	= "Ningún registro (s) encontrado."; //"No record(s) found.";
$lang['go_disposition'] 	= "Disposición"; //"Disposition";
$lang['go_call_duration_in_sec'] = "Duración de la llamada (en segundos)"; //"Call Duration (in sec)";
$lang['go_time'] 		= "Tiempo"; //"Time";
$lang['go_agent_id'] 		= "ID de agente"; //"Agent ID";
$lang['go_date'] 		= "Fecha"; //"Date";
$lang['go_date_caps'] 		= "FECHA"; //"DATE";
$lang['go_inbound_calls_found'] = "Llamada(s) entrante encontrado."; //"inbound call(s) found.";
$lang['go_search_done'] 	= "Buscar hecho."; //"Search done.";
$lang['go_comments'] 		= "Comentarios"; //"Comments";
$lang['go_alt_phone'] 		= "Teléfono Alt"; //"Alt Phone";
$lang['go_email'] 		= "Correo electrónico"; //"Email";
$lang['go_postal_code'] 	= "Código Postal"; //"Postal Code";
$lang['go_state'] 		= "Estado"; //"State";
$lang['go_city'] 		= "Ciudad"; //"City";
$lang['go_address'] 		= "Dirección"; //"Address";
$lang['go_agent'] 		= "Agente"; //"Agent";
$lang['go_call_date_time'] 	= "Llamada La fecha y hora"; //"Call Date & time";
$lang['go_last_name'] 		= "Apellido"; //"Last Name";
$lang['go_first_name'] 		= "Nombre De Pila"; //"First Name";
$lang['go_phone_number'] 	= "Número De Teléfono"; //"Phone Number";
$lang['go_lead_id'] 		= "ID plomo"; //"Lead ID";
$lang['go_close'] 		= "CERRAR"; //"CLOSE";
$lang['go_click_more_info'] 	= "Haga clic para obtener más información"; //"Click for more info";
$lang['go_click_show_more'] 	= "Haga clic aquí para ver más ..."; //"Click here to show more...";
$lang['go_info'] 		= "Información"; // "Info";
$lang['go_agent'] 		= "Agente"; //"Agent";
$lang['go_sale'] 		= "Venta"; //"Sale";
$lang['go_total'] 		= "TOTALES"; //"TOTAL";
$lang['go_sales_count'] 	= "Conde Ventas"; //"Sales Count";
$lang['go_agents_name'] 	= "Agentes Nombre"; //"Agents Name";
$lang['go_inbound_caps'] 	= "VUELTA"; //"INBOUND";
$lang['go_outbound_caps'] 	= "SALIENTE"; //"OUTBOUND";
$lang['go_no_agents_found_time_given'] = "No hay agentes encontraron dentro del tiempo dado."; //"No agents found within the time given.";
$lang['go_agents_caps'] 	= "AGENTES"; //"AGENTS";
$lang['go_total_small'] 	= "Totales"; //"Total";
$lang['go_non_pause'] 		= "Sin Pausa"; //"NonPause";
$lang['go_pause'] 		= "Pausa"; //"Pause";
$lang['go_full_name'] 		= "Nombre Completo"; //"Full Name";
$lang['go_legend_caps'] 	= "LEYENDA"; //"LEGEND";
$lang['go_pls_pick_camp']	= "Por favor elija una campaña."; //"Please pick a campaign.";
$lang['go_avg'] 		= "Avg"; //"Avg";
$lang['go_customer'] 		= "Cliente"; //"Customer";
$lang['go_wrap_up'] 		= "Envolver"; //"Wrap-Up";
$lang['go_dispo'] 		= "Disposición"; //"Dispo";
$lang['go_talk'] 		= "Hablar"; //"Talk";
$lang['go_wait'] 		= "Esperar"; //"Wait";
$lang['go_calls'] 		= "Llamadas"; //"Calls";
$lang['go_agent_time'] 		= "Tiempo Agente"; //"Agent Time";
$lang['go_user_name'] 		= "Nombre de Usuario"; //"User Name";
$lang['go_click_hide'] 		= "Haga clic aquí para ocultar ..."; //"Click here to hide...";
$lang['go_disposition_stats'] 	= "Disposición Estadísticas"; //"Disposition Stats";
$lang['go_lead_count'] 		= "Conde plomo"; //"Lead Count";
$lang['go_total_agents'] 	= "Agentes totales"; //"Total Agents";
$lang['go_total_calls'] 	= "Total de llamadas"; //"Total Calls";
$lang['go_call_statistics'] 	= "Estadísticas de llamadas"; //"Call Statistics";
$lang['go_show'] 		= "Mostrar"; //"Show";
$lang['go_calls_per'] 		= "Llamadas por"; //"Calls per";
$lang['go_date_range'] 		= "Intervalo de fechas"; //"Date Range";
$lang['go_date_range_caps']	= "DATE RANGE"; //"Date Range";
$lang['go_to'] 			= "a"; //"to";
$lang['go_to_caps'] 		= "A"; //"TO";
$lang['go_failed'] 		= "Fracasado"; //"Failed";
$lang['go_submit'] 		= "ENVIAR"; //"SUBMIT";
$lang['go_not_registered']	= "NO REGISTRADO"; //"NOT REGISTERED";
$lang['go_export_fields'] 	= "Exportación Campos"; //"Export Fields";
$lang['go_header_row'] 		= "Fila de encabezado"; //"Header Row";



#######################models/go_reports.php#################################3

$lang['go_note_sel_camp'] 	= "NOTA: Esto incluye el manual de listas de marcación de ID en la campaña seleccionada actual."; //"NOTE: This includes the Manual Dial List ID set on the current selected campaign.";
$lang['go_done_gathering'] 	= "Hecho recolección"; //"Done gathering";
$lang['go_records_analyzing'] 	= "Registros, análisis ..."; //"records, analyzing...";
$lang['go_not_system'] 		= "NO EN EL SISTEMA"; //"NOT IN SYSTEM";
$lang['go_status'] 		= "Estado";//"Status";
$lang['go_status_name'] 	= "Estado Nombre"; //"Status Name";
$lang['go_sub_total_caps'] 	= "TOTAL PARCIAL"; //"SUB-TOTAL";
$lang['go_total_for'] 		= "TOTALES"; //"TOTAL for";
$lang['go_inbound_sales_an_ai_sc'] = "VUELTA VENTAS \ nAGENTS NOMBRE, AGENTES ID, COUNT VENTAS \n"; //"INBOUND SALES\nAGENTS NAME,AGENTS ID,SALES COUNT\n";
$lang['go_outbound_sales_cdt_a_pn_f_l_a_c_s_p_e_an_c'] = "OUTBOUND VENTAS \n call FECHA Y HORA, AGENTE, número de teléfono, nombre, apellido, dirección, ciudad, estado, CÓDIGO POSTAL, correo electrónico, número ALT, COMENTARIOS \n"; //"OUTBOUND SALES\nCALL DATE & TIME,AGENT,PHONE NUMBER,FIRST NAME,LAST NAME,ADDRESS,CITY,STATE,POSTAL CODE,EMAIL,ALT NUMBER,COMMENTS\n";
$lang['go_inbound_camp'] 	= "CAMPAÑA DE ENTRADA"; //"INBOUND CAMPAIGN";
$lang['go_date_aid_pn_t_cd_d'] 	= "FECHA, AGENTE ID, número de teléfono, hora, duración CALL (EN SEC), DISPOSICIÓN \n"; //"DATE,AGENT ID,PHONE NUMBER,TIME,CALL DURATION (IN SEC),DISPOSITION\n";
$lang['go_no_outbound_calls'] 	= "No hay llamadas de salida durante este período de tiempo para estos parámetros."; //"There are no outbound calls during this time period for these parameters.";
$lang['go_all_list_ids'] 	= "Todas las ID de lista bajo"; //"ALL List IDs under";
$lang['go_list_ids'] 		= "ID (s) Lista"; //"List ID(s)";
$lang['go_user_id_c_tc_at_w_t_d_p_w_c'] = "USUARIO, ID, LLAMADAS, RELOJ, AGENTE DE TIEMPO, ESPERE, HABLAR, DISPO, PAUSA, CIERRE, DE CLIENTES"; //"USER,ID,CALLS,TIME CLOCK,AGENT TIME,WAIT,TALK,DISPO,PAUSE,WRAPUP,CUSTOMER";
$lang['go_total_agents'] 	= "TOTALES, AGENTES"; //"TOTALS,AGENTS";
$lang['go_user_name_id_c_at_p_a_w_t_t'] = "NOMBRE DE USUARIO, ID, Llamadas, AGENTE DE TIEMPO, PAUSA PAUSA AVG, Espera, Espera, AVG es Hablar, AVG Hablar, DISPO, DISPO AVG, Wrap-Up, Resumen de la AVG, EL CLIENTE, MEM AVG"; //"USER NAME,ID,CALLS,AGENT TIME,PAUSE,PAUSE AVG,WAIT,WAIT AVG,TALK,TALK AVG,DISPO,DISPO AVG,WRAPUP,WRAPUP AVG,CUSTOMER,CUST AVG";
$lang['go_user_name_id_t_np_p'] = "NOMBRE DE USUARIO, ID, total, NONPAUSE, PAUSA"; // "USER NAME,ID,TOTAL,NONPAUSE,PAUSE";
$lang['go_outbound_sales_an_aid_sc'] = "VENTAS DE SALIDA  \\n AGENTES NOMBRE, AGENTES ID, COUNT VENTAS  \n"; //"OUTBOUND SALES\nAGENTS NAME,AGENTS ID,SALES COUNT\n";


//END OF REPORTS


//START OF AUDIOSTORE/VOICE FILES

##########################go_audiostore.php#######################################

$lang['go_entry_3_char_search'] = "Introduzca por lo menos 3 caracteres para la búsqueda."; //"Please enter at least 3 characters to search.";
$lang['go_pls_inc_wav_file'] 	= "Por favor, incluya un archivo WAV."; //"Please include a .WAV file.";
$lang['go_size'] 		= "TAMAÑO"; //"SIZE";
$lang['go_play'] 		= "JUEGO"; //"PLAY";
$lang['go_pls_upload_audio'] 	= "Por favor, sube sólo los archivos de audio. <br /> Se recomienda encarecidamente <strong> .WAV </strong> los archivos."; //"Please upload only audio files.<br />We strongly recommend <strong>.WAV</strong> files.";
$lang['go_err_uploading'] 	= "Error al subir"; //"Error uploading";
$lang['go_voice_files_tooltip'] = "La pantalla de archivos de voz muestra todos los archivos de voz que haya cargado a su cuenta."; //"The Voice Files screen displays all the voice files that you have uploaded to your account.";
$lang['go_voice_file_upload'] 	= "Archivo Voz para subir"; //"Voice File to Upload";
$lang['go_strongly_recommend'] 	= "Le recomendamos a subir sólo 16bit Mono 8k archivos de audio PCM WAV (.wav)"; //"We STRONGLY recommend uploading only 16bit Mono 8k PCM WAV audio files(.wav)";
$lang['go_clear_search'] 	= "Borrar la búsqueda"; //"Clear Search";
$lang['go_search'] 		= "búsqueda"; //"Search";
$lang['go_voice_files'] 	= "Los archivos de voz"; //"Voice Files";
$lang['go_upload'] = "SUBIR"; //"UPLOAD";
$lang['go_current_file_status'] = "Estado actual del archivo"; //"Current File Status";
$lang['go_good'] = "Bueno"; //"Good";
$lang['go_bad'] = "Malo"; //"Bad";
$lang['go_duplicate'] = "Duplicar"; //"Duplicate";
$lang['go_postal_match'] = "Partido Postal"; //"Postal Match";
$lang['go_con_del'] = "Confirme que quiere eliminar"; //"Confirm to delete";
$lang['go_success_del'] = "eliminado correctamente"; //"successfully deleted";
$lang['go_modify_ingroup'] = "Modificar In-Grupo"; //"Modify In-Group";
$lang['go_color'] = "Color";
$lang['go_active'] = "Activo"; //"Active";
$lang['go_inactive'] = "Inactivo"; //"Inactive";
$lang['go_web_form'] = "Formulario Web"; //"Web Form";
$lang['go_next_agent_call'] = "Llamada del Agente Siguiente"; //"Next Agent Call";
$lang['go_queue_priority'] = "Cola de Prioridad"; //"Queue Priority";
$lang['go_on_hook_ring_time'] = "Colgado duración del timbre"; //"On-Hook Ring Time";
$lang['go_fronter_display'] = "Pantalla Fronter"; //"Fronter Display";
$lang['go_script'] = "guión:"; //"Script:";
$lang['go_script_caps'] = "GUIÓN"; //"SCRIPT";
$lang['go_ignore_list_script'] = "No haga caso de anulación de la lista de secuencias de commandos"; //"Ignore List Script Override";
$lang['go_get_call_launch'] = "Obtén llamada lanzamiento"; //"Get Call Launch";
$lang['go_webform'] = "FORMULARIO WEB"; //"WEBFORM";
$lang['go_form_caps'] = "FORMULARIO"; //"FORM";
$lang['go_transfer_conf'] = "Transfer-Conf DTMF";
$lang['go_transfer_conf_number'] = "Número Transfer-Conf"; //"Transfer-Conf Number";
$lang['go_timer_action'] = "Acción Temporizador"; //"Timer Action";
$lang['go_d1_dial'] = "D1_MARCAR"; //"D1_DIAL";
$lang['go_d2_dial'] = "D2_MARCAR"; //"D2_DIAL";
$lang['go_d3_dial'] = "D3_MARCAR"; //"D3_DIAL";
$lang['go_d4_dial'] = "D4_MARCAR"; //"D4_DIAL";
$lang['go_d5_dial'] = "D5_MARCAR"; //"D5_DIAL";
$lang['go_message_only'] = "MENSAJE_ÚNICO"; //"MESSAGE_ONLY";
$lang['go_hangup'] = "COLGAR"; //"HANGUP";
$lang['go_callmenu'] = "MENÚ LLAMADA"; //"CALLMENU";
$lang['go_exten'] = "AMPLIACIÓN"; //"EXTENSION";
$lang['go_in_group'] = "EN-GRUPO"; //"IN-GROUP";
$lang['go_in_groups'] = "EN_GRUPO"; //"IN_GROUP";
$lang['go_timer_action_msg'] = "Temporizador de mensaje de acción"; //"Timer Action Message";
$lang['go_timer_action_seconds'] = "Segundos Temporizador Acción"; //"Timer Action Seconds";
$lang['go_timer_action_destination'] = "Temporizador Acción Destino"; //"Timer Action Destination";
$lang['go_drop_call_seconds'] = "Caiga segundos de llamada"; //"Drop Call Seconds";
$lang['go_drop_action'] = "Acción Gota"; //"Drop Action";
$lang['go_drop_actions'] = "ACCIóN_GOTA"; //"DROP_ACTION";
$lang['go_msg'] = "MENSAJE"; //"MESSAGE";
$lang['go_voicemail'] = "CORREO DE VOZ"; //"VOICEMAIL";
$lang['go_drop_exten'] = "Gota Exten"; //"Drop Exten";
$lang['go_vm'] = "Correo de voz"; //"Voicemail";
$lang['go_drop_transfer_group'] = "Gota Grupo de Transferencia"; //"Drop Transfer Group";
$lang['go_call_time'] = "Hora de llamada"; //"Call Time";
$lang['go_after_hours_action'] = "Después de horas de acción"; //"After Hours Action";
$lang['go_after_hours_msg_filename'] = "Después de horas de mensajes Nombre de archivo"; //"After Hours Message Filename";
$lang['go_audio_chooser'] = "Audio Selector"; //"Audio Chooser";
$lang['go_after_hours_exten'] = "Después de horas de Extensión"; //"After Hours Extension";
$lang['go_after_hours_vm'] = "Después de horas de correo de voz"; //"After Hours Voicemail";
$lang['go_vm_chooser'] = "Correo de voz selector"; //"Voicemail Chooser";
$lang['go_no_delay_call_route'] = "Sin retardo Ruta de llamada"; //"No Delay Call Route";
$lang['go_after_hours_transfer_group'] = "Después Grupo Horas Transferencia"; //"After Hours Transfer Group";
$lang['go_no_agents_no_queueing'] = "No Agentes No Queueing"; //"No Agents No Queueing";
$lang['go_no_paused'] = "NO_EN_PAUSA"; //"NO_PAUSED";
$lang['go_no_agent_no_queue_action'] = "Ninguna acción agente No Queue"; //"No Agent No Queue Action";
$lang['go_ingroup'] = "ENDOGRUPO"; //"INGROUP";
$lang['go_welcome_msg_filename'] = "Mensaje de Bienvenida Nombre"; //"Welcome Message Filename";
$lang['go_play_welcome_msg'] = "Juega Mensaje de Bienvenida"; //"Play Welcome Message";
$lang['go_always'] = "SIEMPRE"; //"ALWAYS";
$lang['go_never'] = "NUNCA"; //"NEVER";
$lang['go_if_wait_only'] = "SI_SÓLO_ESPERA"; //"IF_WAIT_ONLY";
$lang['go_yes_unless_nodelay'] = "SÍ_A_MENOS_NODELAY"; //"YES_UNLESS_NODELAY";
$lang['go_moh_context'] = "Música en espera Contexto"; //"Music On Hold Context";
$lang['go_moh_chooser'] = "Música en espera Selector"; //"Moh Chooser";
$lang['go_on_hold_prompt_filename'] = "En espera pronta nombre"; //"On Hold Prompt Filename";
$lang['go_on_hold_prompt_interval'] = "En espera de intervalo pronta"; //"On Hold Prompt Interval";
$lang['go_on_hold_prompt_no_block'] = "En espera pedirá ningún bloque"; //"On Hold Prompt No Block";
$lang['go_on_hold_prompt_seconds'] = "En prontas segundos de espera"; //"On Hold Prompt Seconds";
$lang['go_play_place_line'] = "Juega lugar en la fila"; //"Play Place in Line";
$lang['go_play_estimated_hold_time'] = "Juega el tiempo estimado de espera"; //"Play Estimated Hold Time";
$lang['go_calculate_estimated_hold_seconds'] = "Calcular segundos de espera estimado"; //"Calculate Estimated Hold Seconds";
$lang['go_estimated_hold_time_min_filename'] = "Nombre de archivo en un tiempo mínimo estimado bodega"; //"Estimated Hold Time Minimum Filename";
$lang['go_estimated_hold_time_min_prompt_no_block'] = "Tiempo estimado de retención mínimo pronta ningún bloque"; //"Estimated Hold Time Minimum Prompt No Block";
$lang['go_estimated_hold_time_min_prompt_seconds'] = "Tiempo estimado de espera segundo mínimas prontas"; //"Estimated Hold Time Minimum Prompt Seconds";
$lang['go_wait_time_option'] = "Espere opción de tiempo"; //"Wait Time Option";
$lang['go_press_stay'] = "PRENSA_ESTANCIA"; //"PRESS_STAY";
$lang['go_press_vmail'] = "PRENSA_VOICEMAIL"; //"PRESS_VMAIL";
$lang['go_press_exten'] = "PRENSA_EXTEN"; //"PRESS_EXTEN";
$lang['go_press_callmenu'] = "PRENSA_LLAMADA_MENÚ"; //"PRESS_CALLMENU";
$lang['go_press_cid_callback'] = "PRENSA_CID_RETROLLAMADA"; //"PRESS_CID_CALLBACK";
$lang['go_press_ingroup'] = "PRENSA_ENDOGRUPO"; //"PRESS_INGROUP";
$lang['go_click_tabs_swap_logical_sections'] = "Haga clic en las pestañas para cambiar entre el contenido que se divide en secciones lógicas."; //"Click tabs to swap between content that is broken into logical sections.";
$lang['go_nones'] = "ninguno"; //"none";
$lang['go_agent_ranks_inbound_group'] = "AGENTE RANGOS PARA ESTE GRUPO DE ENTRADA"; //"AGENT RANKS FOR THIS INBOUND GROUP";
$lang['go_uniqueid_status_prefix'] = "Identificación del único Estado Prefijo"; //"Uniqueid Status Prefix";
$lang['go_modify'] = "MODIFICAR"; //"MODIFY";
$lang['go_uniqueid_status_display'] = "Identificación única pantalla de estado"; //"Uniqueid Status Display";
$lang['go_disabled'] = "DISCAPACITADOS"; //"DISABLED";
$lang['go_enabled'] = "HABILITADO"; //"ENABLED";
$lang['go_enabled_prefix'] = "HABILITADO_PREFIJO"; //"ENABLED_PREFIX";
$lang['go_enabled_preserve'] = "HABILITADO_PRESERVAR"; //"ENABLED_PRESERVE";
$lang['go_exten_append_cid'] = "Extensión Append CID"; //"Extension Append CID";
$lang['go_add_lead_url'] = "Añadir URL Plomo"; //"Add Lead URL";
$lang['go_dispo_call_url'] = "URL llamada Disposición"; //"Dispo Call URL";
$lang['go_start_call_url'] = "Iniciar URL llamada"; //"Start Call URL";
$lang['go_stats_percent_calls_answered'] = "Estadísticas Porcentaje de llamadas contestadas dentro de X segundos"; //"Stats Percent of Calls Answered Within X seconds ";
$lang['go_in_group_recording_filename'] = "En-Grupo Grabación Nombre"; //"n-Group Recording Filename";
$lang['go_in_group_recording_override'] = "En-grupo de anulación de grabación"; //"In-Group Recording Override";
$lang['go_ondemand'] = "BAJO DEMANDA"; //"ONDEMAND";
$lang['go_allcalls'] = "TODAS LAS LLAMADAS"; //"ALLCALLS";
$lang['go_allforce'] = "TODA LA FUERZA"; //"ALLFORCE";
$lang['go_hold_recall_transfer_in_group'] = "Rellamada de transferencia en - Grupo"; //"Hold Recall Transfer In-Group";
$lang['go_default_group_alias'] = "Por defecto Grupo Alias"; //"Default Group Alias";
$lang['go_default_transfer_group'] = "Por defecto Transferencia Grupo"; //"Default Transfer Group";
$lang['go_agent_alert_delay'] = "Agente de retraso en la alerta"; //"Agent Alert Delay";
$lang['go_agent_alert_filename'] = "Alerta Agente Nombre"; //"Agent Alert Filename";
$lang['go_hold_time_option_callback_list_id'] = "Mantenga opción de tiempo Identificación del Listado de devolución de llamada"; //"Hold Time Option Callback List ID";
$lang['go_hold_time_option_after_press_filename'] = "Mantenga opción de tiempo Después de Prensa Nombre"; //"Hold Time Option After Press Filename";
$lang['go_hold_time_option_press_filename_seconds'] = "Sostenga tiempo oprima la opción de nombre de archivo segundos"; //"Hold Time Option Press Filename Seconds";
$lang['go_hold_time_option_press_no_block'] = "Mantenga opción de tiempo Pulse No Bloquear"; //"Hold Time Option Press No Block";
$lang['go_hold_time_option_press_filename'] = "Mantenga Opción hora Pulse Nombre del archivo"; //"Hold Time Option Press Filename";
$lang['go_hold_time_option_transfer_in_group'] = "Sostenga Tiempo de transferencia Opción A - Grupo"; //"Hold Time Option Transfer In-Group";
$lang['go_hold_time_option_vm'] = "Sostenga Tiempo Opción de correo de voz"; //"Hold Time Option Voicemail";
$lang['go_hold_time_option_callmenu'] = "Tiempo de Espera menú Opción de Compra"; //"Hold Time Option Callmenu";
$lang['go_hold_time_option_exten'] = "Sostenga Tiempo Extensión Opción"; //"Hold Time Option Extension";
$lang['go_hold_time_option_min'] = "Mantenga Opción Tiempo mínimo"; //"Hold Time Option Minimum";
$lang['go_hold_time_option_seconds'] = "Mantenga opción segundo tiempo"; //"Hold Time Option Seconds";
$lang['go_hold_time_third_option'] = "Sostenga Tiempo Tercera Opción"; //"Hold Time Third Option";
$lang['go_hold_time_second_option'] = "Tiempo de Espera segunda opción"; //"Hold Time Second Option";
$lang['go_wait_time_second_option'] = "Tiempo de Espera de Segunda Opción"; //"Wait Time Second Option";
$lang['go_wait_time_option_seconds'] = "Tiempo de espera segundos Opción"; //"Wait Time Option Seconds";
$lang['go_wait_time_option_exten'] = "Tiempo de espera de Extensión Opción"; //"Wait Time Option Extension";
$lang['go_wait_time_option_callmenu'] = "Tiempo de espera del menú Opción de Compra"; //"Wait Time Option Callmenu";
$lang['go_wait_time_option_vm'] = "Tiempo de espera Opción de correo de voz"; //"Wait Time Option Voicemail";
$lang['go_wait_time_option_transfer_in_group'] = "Tiempo de espera de transferencia Opción A - Grupo"; //"Wait Time Option Transfer In-Group";
$lang['go_wait_time_option_press_filename'] = "Tiempo de espera Opción Pulse Nombre del archivo"; //"Wait Time Option Press Filename";
$lang['go_wait_time_option_press_no_block'] = "Tiempo de espera Opción Pulse No Bloquear"; //"Wait Time Option Press No Block";
$lang['go_wait_time_option_press_filename_seconds'] = "Tiempo de espera oprima la opción segundos Nombre de archivo"; //"Wait Time Option Press Filename Seconds";
$lang['go_wait_time_option_after_press_filename'] = "Espere opción de tiempo Después de Prensa Nombre"; //"Wait Time Option After Press Filename";
$lang['go_wait_time_option_callback_list_id'] = "Espere opción de tiempo Identificación del Listado de devolución de llamada"; //"Wait Time Option Callback List ID";
$lang['go_wait_hold_option_priority'] = "Espere prioridad opción bodega"; //"Wait Hold Option Priority";
$lang['go_wait_caps'] = "ESPERE"; //"WAIT";
$lang['go_both'] = "AMBOS"; //"BOTH";
$lang['go_estimated_hold_time_option'] = "Opción de tiempo de espera estimado"; //"Estimated Hold Time Option";
$lang['go_call_menu'] = "LLAMADA_MENÚ"; //"CALL_MENU";
$lang['go_callerid_callback'] = "ID DE LLAMADAS _ RETROLLAMADA"; //"CALLERID_CALLBACK";
$lang['go_lower'] = "Inferior "; //"Lower";
$lang['go_higher'] = "Superior"; //"Higher";
$lang['go_case_sensitive'] = "distingue mayúsculas y minúsculas"; //"case sensitive";
$lang['go_default_value'] = "Valor predeterminado"; //"Default Value";
//model
$lang['go_group_not_added_already_id'] = "GRUPO NO AÑADIDO - ya hay un grupo en el sistema con este ID"; //"GROUP NOT ADDED - there is already a group in the system with this ID";
$lang['go_group_not_added_pls_back'] = "GRUPO NO AÑADIDO - Por favor, volver atrás y mirar a los datos introducidos. Identificación del grupo debe tener entre 2 y 20 caracteres de longitud y contener '- +'. Nombre del grupo y color grupo deben tener al menos 2 caracteres de longitud."; //"GROUP NOT ADDED - Please go back and look at the data you entered\n <br>Group ID must be between 2 and 20 characters in length and contain no ' -+'.\n <br>Group name and group color must be at least 2 characters in length";
$lang['go_added_missing_user'] = "el usuario añadió que falta a la mesa viga"; //"added missing user to viga table";
$lang['go_user'] = "USARIO"; //"USER";
$lang['go_selected_caps'] = "SELECCIONADO"; //"SELECTED";
$lang['go_rank_caps'] = "RANGO"; //"RANK";
$lang['go_calls_today'] = "LLAMADAS DE HOY"; //"CALLS TODAY";
$lang['go_sounds_list_permission'] = "USUARIO sounds_list NO TIENE PERMISO PARA VER LOS SONIDOS LISTA"; //"sounds_list USER DOES NOT HAVE PERMISSION TO VIEW SOUNDS LIST";
$lang['go_sounds_list_csc_nactive'] = "sounds_list CENTRAL CONTROL DE SONIDO no esta activado"; //"sounds_list CENTRAL SOUND CONTROL IS NOT ACTIVE";
$lang['go_modify_fields'] = "modificar campos"; //"modify fields";
$lang['go_summary_fields'] = "Resumen de los campos"; //"Summary of fields";
$lang['go_create_custom_field'] = "Crear campo personalizado"; //"Create custom field";
$lang['go_view_custom_fields'] = "Ver campos personalizados"; //"View custom fields";
$lang['go_custom_listings'] = "Ver anuncios personalizados"; //"View custom listings";
$lang['go_rank'] = "Rango"; //"Rank";
$lang['go_label'] = "Etiqueta"; //"Label";
$lang['go_name'] = "Nombre"; //"Name";
$lang['go_type'] = "Tipo"; //"Type";
$lang['go_action'] = "Acción"; //"Action";
$lang['go_modify_s'] = "Modificar"; //"Modify";
$lang['go_del'] = "Borrar"; //"delete";

##################################MUSIC ON HOLD###############################################
$lang['go_err_permission_view'] = "Error: No tienes permiso para ver esta página."; //"Error: You do not have permission to view this page.";
$lang['go_file_type_wav'] = "Tipo de archivo debe estar en formato wav."; //"File type should be in wav format.";
$lang['go_group_not_added_data'] = "GRUPO NO AÑADIDO - Por favor, volver atrás y mirar a los datos introducidos"; //"GROUP NOT ADDED - Please go back and look at the data you entered";
$lang['go_moh_id_caps'] = "Música de Identificación del asimiento"; //"MOH ID";
$lang['go_moh_name_caps'] = "Música en nombre de bodega"; //"MOH NAME";
$lang['go_status_caps'] = "ESTADO"; //"STATUS";
$lang['go_group_caps'] = "GRUPO"; //"GROUP";
$lang['go_action_caps'] = "ACCIÓN"; //"ACTION";
$lang['go_active_caps'] = "ACTIVO"; //"ACTIVE";
$lang['go_inactive_caps'] = "INACTIVO"; //"INACTIVE";
$lang['go_all_caps'] = "ALL"; //"ALL";
$lang['go_all_user_groups_caps'] = "ALL USER GROUPS"; //"ALL USER GROUPS";
$lang['go_del_moh_id'] = "ELIMINAR LA MÚSICA EN ESPERA ID"; //"DELETE MOH ID";
$lang['go_modify_moh_id'] = "MODIFICAR LA MÚSICA EN ESPERA ID"; //"MODIFY MOH ID";
$lang['go_no_records_found'] = "Ningún registro(s) encontrado."; //"No record(s) found.";
$lang['go_random_order_caps'] = "ORDEN AL AZAR"; //"RANDOM ORDER";
$lang['go_pls_sel_moh_id'] = "Por favor, seleccione una música en espera ID."; //"Please select an MOH ID.";
$lang['go_del_sel_moh_id'] = "¿Seguro que quieres eliminar la música seleccionada en espera ID"; //"Are you sure you want to delete the selected MOH ID";
$lang['go_del_moh'] = "¿Seguro que quieres eliminar"; //"Are you sure you want to delete";
$lang['go_moh_entry'] = "MÚSICA EN ESPERA ENTRADA"; //"MOH ENTRY";
$lang['go_del_filename'] = "¿Seguro que quieres eliminar"; //"Are you sure you want to delete";
$lang['go_from_list_files'] = "de la lista de archivos?"; //"from the list of files?";
$lang['go_moh_file_entry'] = "MÚSICA EN LA ENTRADA ARCHIVO DE RETENCION"; //"MOH FILE ENTRY ";
$lang['go_add_new_moh'] = "Añadir Nueva Música en espera"; //"Add New Music On Hold";
$lang['go_music_on_hold'] = "Música en espera"; //"Music On Hold";
$lang['go_moh_listing'] = "Música en espera (MOH) Listados"; //"Music On Hold (MOH) Listings";
$lang['go_modify_moh'] = "MODIFICAR LA MÚSICA EN ESPERA"; //"MODIFY MUSIC ON HOLD";
$lang['go_moh_name'] = "Música en espera Nombre"; //"Music on Hold Name";
$lang['go_user_group'] = "Grupo de Usuarios"; //"User Group";
$lang['go_random_order'] = "Orden Aleatorio"; //"Random Order";
$lang['go_add_audio_file'] = "Añadir un archivo de audio"; //"Add an Audio File";
$lang['go_moh'] = "Música en espera"; //"Music On Hold";
$lang['go_moh_id_3_chars'] = "Música de Identificación del asimiento no debe estar vacío o debe ser de al menos 3 caracteres de longitud."; //"MoH ID should not be empty or should\nbe at least 3 characters in length.";
$lang['go_moh_id_navailable'] = "Música de Identificación del asimiento no disponible."; //"MoH ID Not Available.";
$lang['go_moh_wizard'] = "Música en espera asistente"; //"Music on Hold Wizard";
$lang['go_add_new_moh'] = "Añadir Nueva Música en espera"; //"Add New Music on Hold";
$lang['go_moh_id'] = "Música de Identificación Hold"; //"Music on Hold ID";
$lang['go_moh_name'] = "Música en espera Nombre"; //"Music on Hold Name";
$lang['go_err_permission_view'] = "Error: No tienes permiso para ver esta página."; //"Error: You do not have permission to view this page.";
$lang['go_success_caps'] = "ÉXITO"; //"SUCCESS";
$lang['go_sel_audio_upload'] = "Seleccione un archivo de audio para cargar"; //"Select an audio file to upload";
$lang['go_all_user_groups'] = "All User Groups"; //"All User Groups";
$lang['go_moh_item'] = "música elemento de retención"; //"music on hold item";



####################################### Interactive Voice Response (IVR) Menus ################################

$lang['go_ivr_menus'] = "Interactive Voice Response (IVR) Menus";
$lang['go_menu_id_caps'] = "MENU ID";
$lang['go_descriptions_caps'] = "DESCRIPTIONS";
$lang['go_prompt_caps'] = "PROMPT";
$lang['go_timeout_caps'] = "TIMEOUT";
$lang['goaction_caps'] = "ACTION";
$lang['go_modify_ivr'] = "MODIFY IVR";
$lang['go_del_ivr'] = "DELETE IVR";
$lang['go_del_sel'] = "Delete Selected";
$lang['go_modify_callmenu'] = "MODIFY CALLMENU";
$lang['go_menu_id'] = "Menu ID";
$lang['go_menu_name'] = "Menu Name";
$lang['go_menu_prompt'] = "Menu Prompt";
$lang['go_menu_timeout'] = "Menu Timeout";
$lang['go_menu_timeout_prompt'] = "Menu Timeout Prompt";
$lang['go_menu_invalid_prompt'] = "Menu Invalid Prompt";
$lang['go_menu_repeat'] = "Menu Repeat";
$lang['go_menu_time_check'] = "Menu Time Check";
$lang['go_call_time'] = "Call Time";
$lang['go_tracks_call_real_time_report'] = "Track Calls in Real-Time Report";
$lang['go_tracking_group'] = "Tracking Group";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_call_menu_options'] = "Call Menu Options";
$lang['go_option'] = "Option";
$lang['go_description'] = "Description";
$lang['go_audio_file'] = "Audio File";
$lang['go_route'] = "route";
$lang['go_audio_chooser'] = "audio chooser";
$lang['go_save'] = "Save";
$lang['go_finish'] = "Finish";






############################# Admin Settings ##############




$lang['go_agent_id'] = "Identificación del agente"; //"Agent ID";
$lang['go_none'] = "NINGUNO";//"NONE";
$lang['go_ignore_list_script_over'] ="No haga caso de anulación de la lista de secuencias de comandos"; //"Ignore List Script Override";
$lang['go_admin'] = "ADMIN"; //"ADMINISTRADOR"; //"ADMIN";
$lang['go_phone'] = "Teléfono"; //"Phone";
$lang['go_adv_settings'] = "CONFIGURACIóN AVANZADA"; //"ADVANCE SETTINGS"; //
$lang['go_save_settings'] = "GUARDAR LOS AJUSTES"; //"SAVE SETTINGS"; //
$lang['go_codecs'] = "Codecs";

// Interactive Voice Response (IVR) Menus tab
$lang['go_displaying'] = "Viendo"; //"Displaying";


// Add New Call Menu/Modify
$lang['go_audio_chooser'] = "selector de audio"; //"audio chooser";
$lang['go_invalid_ip_add'] = "La dirección IP no"; //"Invalid IP address";
$lang['go_back'] = "Espalda"; //"Back";
$lang['] = "Dial Prefix";go_err_permission_view'] = "Error: No tienes permiso para ver esta página."; // "Error: You do not have permission to view this page.";
$lang['go_added_new_ug'] = "Añadido nuevo grupo de usuarios"; //"Added New User Group";
$lang['go_added_new_phone'] = "Teléfono Añadido"; //"Added Phone";
$lang['go_added_new_server'] = "Añadido Nuevo Servidor"; //"Added New Server";
$lang['go_add_new_ga'] = "Añadir nuevo Grupo de acceso"; //"Add new Group Access";
$lang['go_del'] = "Borrar"; //"Delete";


// Add new Music on hold
$lang['go_close'] = "Cerca";//"Close"; 
$lang['go_search_IP_admin'] = "Búsqueda de direcciones Usuario / IP dentro de Administrador Registro"; //"Search for User/IP Address within Admin Logs";
$lang['go_del'] = "Borrar"; //"Delete"; 


// Voice Files
$lang['go_voice_files'] = "Los archivos de voz"; //"Voice Files"; 
$lang['go_no'] = "NO"; //"NO";
$lang['go_upload'] = "Subir"; // "Upload"; 


// [1] Statistical Report
$lang['go_statistical_report'] = "Informe Estadístico"; //"Statistical Report"; //



// [3] Agent Performance Details
$lang['go_full_name'] = "Nombre Completo"; //"Full Name";




// [5] Sales Per Agent
$lang['go_outbound_camp'] = "Campaña saliente"; //"Outbound Campaign";
$lang['go_inbound'] = "Entrante"; //"Inbound";

// [6] Sales Tracker
$lang['go_first_name'] = "Nombre de pila"; //"First Name";
$lang['go_last_name'] = "Apellido"; //"Last Name";



// [8] Export Call Report
$lang['go_yes'] =  "SÍ"; //"YES";
$lang['go_campaigns'] = "Campañas"; //"Campaigns";
$lang['go_no_campaigns'] =  "No hay campañas que utilizan este soporte"; //"There are no campaigns using this carrier";
$lang['go_carrier_not_available'] = "Identificación del Transportista no disponible"; //"Carrier ID Not Available";
$lang['go_jgvp_already'] = "Una cuenta JustGoVoIP ya existen"; //"A JustGoVoIP account already exist";
$lang['go_all'] = "ALL"; //"ALL";
$lang['go_submit'] = "Presentar"; //"Submit"; 

// [9] Dashboard
$lang['go_dashboard'] = "Salpicadero"; //"Dashboard"; 


##################### Admin Settings ##########################

//Call times

$lang['go_success_ct'] = "Éxito! \n hora de llamada"; //"Success!\n\nCall time";
$lang['go_has_been_modified'] = "ha sido modificado."; //"has been modified.";
$lang['go_success_state_ct'] = "Éxito! \n\n Estado duración de la llamada"; //"Success!\n\nState call time ";
$lang['go_success_state_call_time_rule'] = "Éxito! \n\n regla de tiempo de llamada Estado"; //"Success!\n\nState call time rule";
$lang['go_pls_sel_state_call_time_rule'] = "Por favor, seleccione una regla de tiempo de llamada Estado."; //"Please select a state call time rule.";
$lang['go_want_to_del'] = "¿Seguro que quieres eliminar"; //"Are you sure you want to delete";
$lang['go_from_list'] = "de la lista"; //"from the list?";
$lang['go_has_been_del'] = "ha sido eliminado."; //"has been deleted.";
$lang['go_call_time_id_navailable'] = "Llame Identificación del Tiempo no disponible."; //"Call Time ID Not Available.";
$lang['go_success_added_new_call_time_id'] = "ÉXITO: Añadido Identificación del Nuevo Tiempo de llamada"; //"SUCCESS: Added New Call Time ID.";
$lang['go_call_time_already_exist'] = "Un Identificación del tiempo de llamada ya existe.";//"A call time id already exist.";
$lang['go_call_time_already_exist'] = "Un Identificación del tiempo de llamada ya existe."; //"A call time id already exist.";
$lang['go_state_call_time_id_navailable'] = "Identificación del Estado llamada del tiempo no disponible."; //"State Call Time ID Not Available.";
$lang['go_success_added_new_state_ct'] = "ÉXITO: Añadido Identificación del Nuevo Estado Duración de llamada."; //"SUCCESS: Added New State Call Time ID.";
$lang['go_all_user_groups_caps'] = "ALL USER GROUPS"; //"ALL USER GROUPS";

//Carriers
$lang['go_pls_sel_carrier'] = "Seleccione un portador."; //"Please select a Carrier.";
$lang['go_del_sel_carrier'] = "¿Seguro que quieres eliminar el portador seleccionado"; //"Are you sure you want to delete the selected Carrier";
$lang['go_modify_carrier'] = "MODIFICAR TRANSPORTISTA"; //"MODIFY CARRIER";
$lang['go_del_carrier'] = "BORRAR TRANSPORTISTA"; //"DELETE CARRIER";
$lang['go_custom_caps'] = "COSTUMBRE"; //"CUSTOM";
$lang['go_comma_delimited'] = "delimitado por comas ej. speex, G711"; //"comma delimited eg. speex,g711";
$lang['go_enter_custom_dtmf'] = "Introducir a medida DTMF"; //"Enter Custom DTMF";
$lang['go_not_active_caps'] = "NO ACTIVO"; //"NOT ACTIVE";
$lang['go_live_agent_caps'] = "AGENTE EN VIVO"; //"LIVE AGENT";
$lang['go_external_caps'] = "EXTERNO"; //"EXTERNAL";

// [1] Admin Logs
$lang['go_search_logs'] = "Registros de la búsqueda"; //"Search Logs";
$lang['go_db_query'] = "Consulta de bases de datos"; //"DB QUERY";
$lang['go_show_query'] = "espectáculo consulta"; //"show query";
$lang['go_logs'] = "Registros"; //"Logs";
$lang['go_logs_s'] = "Registros"; //"logs";
$lang['no_logs_found'] = "Ningún registro(s) encontrado"; //"No log(s) found.";
$lang['go_user'] = "Usuario"; //"User"; 
$lang['go_ip_address'] = "Dirección IP"; //"IP Address";
$lang['go_date'] = "Fecha"; //"DATE";
$lang['go_details'] = "Detalles"; //"Details";
$lang['go_db_query'] = "Consulta de bases de datos"; //"DB Query";


//Multi-tenant
$lang['go_del_tenant_warning'] = "ADVERTENCIA! ¿Seguro que quieres eliminar el inquilino seleccionado"; //"WARNING! Are you sure you want to delete the selected Tenant";
$lang['go_del_tenant_warning1'] = "Esto eliminará todas las entradas bajo el inquilino seleccionado"; //"This will DELETE ALL entries under the selected tenant";
$lang['go_del_tenant_warning2'] = "Haga clic en Aceptar para continuar."; //"Click OK to continue.";
$lang['go_users'] = "Usarios"; //"Users";
$lang['go_warning_want_del'] = "ADVERTENCIA! ¿Seguro que quieres eliminar"; //"WARNING! Are you sure you want to delete";
$lang['go_del_all_entries'] = "Esto eliminará todas las entradas bajo esta inquilino."; //"This will DELETE ALL entries under this tenant.";
$lang['go_users_a'] = "> Usuarios"; //"> Users";
$lang['go_campaigns_a'] = "> Campañas"; //"> Campaigns";
$lang['go_list_ids_a'] = "> Lista de identificadores"; //"> List IDs";
$lang['go_phones_a'] = "> Telefonía"; //"> Phones";
$lang['go_leads_uploaded_a'] = "> Leads subidos"; //"> Leads uploaded";
$lang['go_click_continue_a'] = "Haga clic en Aceptar para continuar."; //"Click OK to continue.";
$lang['go_campaigns'] = "Campañas"; //"Campaigns";
$lang['go_list_ids'] = "Lista de identificadores"; //"List IDs";
$lang['go_list_id'] = "Lista de identificación"; //"List ID";
$lang['go_list_name'] = "Nombre de la lista"; //"LIST NAME";
$lang['go_late_uploaded'] = "Leads subidos"; //"Leads uploaded";
$lang['go_bal'] = "Equilibrio"; //"BALANCE";
$lang['go_tenant_id'] = "Identificación del Inquilino"; //"TENANT ID";
$lang['go_tenant_name'] = "Nombre del Inquilino"; //"TENANT NAME";
$lang['go_num_agents'] = "NúMERO DE AGENTES"; //"NUMBER OF AGENTS";
$lang['go_show_bal'] = "Mostrar crédito"; //"Show Balance";
$lang['go_show'] = "Mostrar"; //"Show";
$lang['go_modify_tenant_id'] = "Modificar Identificación del inquilino"; //"MODIFY TENANT ID";
$lang['go_del_tenant_id'] = "Eliminar Identificación del inquilino"; //"DELETE TENANT ID";
$lang['go_del_tenant'] = "Inquilino Suprimido"; //"Deleted Tenant";
$lang['go_del_user_camp_list'] = "Es admin y todos los inicios de sesión de usuario debajo de ella y también elimina la Campaña (s)"; //"it's admin and all user logins under it and also deleted the Campaign(s)";
$lang['go_and_list_id'] = "y la Lista de Identificación(s)"; //"and List ID(s)";
$lang['go_admin_campaign'] = "como la sesión de administrador y la nueva campaña creada"; //"as the Admin Login and created new Campaign";
$lang['go_ap_using'] = "Agentes/Móviles utilizando"; //"Agents/Phones using";
$lang['go_with'] = "Con"; //"With";
$lang['go_added_tenant'] = "Se ha añadido un nuevo inquilino"; //"Added a New Tenant";
$lang['go_autogen'] = "Identificación de lista generada automáticamente"; //"Auto-Generated ListID";
$lang['go_manual'] = "MANUAL"; //"MANUAL";
$lang['go_random'] = "aleatorio"; //"random";
$lang['go_ap'] = "Teléfono del Agente"; //"Agent Phone";
$lang['go_admin_phone'] = "administrador del teléfono"; //"Admin Phone";
$lang['go_down'] = "ABAJO"; //"DOWN";

$lang['go_tenant_entry'] = "ENTRADA INQUILINO"; //"TENANT ENTRY";
$lang['go_add_new_tenant'] ="Añadir nuevo inquilino"; // "Add New Tenant";
$lang['go_modify_user'] = "Modificar Usuario"; //"Modify User";
$lang['go_phone_passs'] = "Contraseña del teléfono"; //"Phone Pass";
$lang['go_phone_login'] = "Teléfono entrada"; //"Phone Login";
$lang['go_tenant_short'] = "Identificación del Inquilino demasiado corto."; //"Tenant ID too short.";
$lang['go_tenant_empty'] = "Nombre del Inquilino no debe estar vacío."; //"Tenant name should not be empty.";
$lang['go_admin_login_short'] = "Entrar demasiado corto administrador."; //"Admin login too short.";
$lang['go_admin_login_navailable'] = "Administrador de inicio de sesión no está disponible."; //"Admin login not available.";
$lang['go_admin_pass_short'] = "Contraseña de administrador demasiado corto."; //"Admin password too short.";
$lang['go_error'] = "Error"; //"Error";
$lang['go_too_short'] = "Demasiado Corto"; //"Too Short";
$lang['go_weak'] = "Débil"; //"Weak";
$lang['go_strong'] = "Fuerte"; //"Strong";
$lang['go_good'] = "Bueno"; //"Good";
$lang['go_add_multitenant_wizard'] = "Multi-Tenant Asistente"; //"Multi-Tenant Wizard";
$lang['go_add_new_tenant'] = "Añadir nuevo inquilino"; //"Add New Tenant";
$lang['go_tenant_id'] = "Identificación del Inquilino"; //"Tenant ID";
$lang['go_tenant_name'] = "Nombre del Inquilino"; //"Tenant Name";
$lang['go_admin_login'] = "Administrador de inicio de sesión"; //"Admin Login";
$lang['go_admin_pass'] = "Contraseña de administrador"; //"Admin Password";
$lang['go_group_template'] = "Plantillas de grupo"; //"Group Template";
$lang['go_create_ct'] = "Puede crear los tiempos de llamada"; //"Can Create Call Times";
$lang['go_create_carriers'] = "Se puede crear transportistas"; //"Can Create Carriers";
$lang['go_create_phones'] = "Se puede crear Móviles"; //"Can Create Phones";
$lang['go_create_vm'] = "Puede crear mensajes de voz"; //"Can Create Voicemails";

$lang['go_agent_cnt'] = "Conde Agente";
$lang['go_allowed_phone_exten'] = "Extensión telefónica mascotas";
$lang['go_allowed_to_create'] = "Tu eres todavía les permite crear";
$lang['go_phone_exten'] = "Extensiones Telefónicas";
$lang['go_reached_limit_phone'] = "Usted ha llegado al límite en la creación de nuevas extensiones de teléfono.";

$lang['go_agent_phone_def_pass'] = "Agente / Teléfono Contraseña por defecto"; //"Agent/Phone Default Password";
$lang['go_modify_tenant'] = "MODIFICAR INQUILINO"; //"MODIFY TENANT";
$lang['go_super_admin'] = "Superadministrador"; //"Super Admin";
$lang['go_num_admins'] = "Número de Administradores"; //"Number of Admins";
$lang['go_remaining_bal'] = "Saldo restante"; //"Remaining Balance";
$lang['go_access_ct'] = "Acceso de llams"; //"Access Call Times";
$lang['go_access_carriers'] = "Los portadores de acceso"; //"Access Carriers";
$lang['go_access_phones'] = "Teléfonos de acceso:"; //"Access Phones:";
$lang['go_access_vm'] = "Acceso Correos de voz"; //"Access Voicemails";
$lang['go_phone_exten_tenant'] = "TELÉFONOS eXten utilizados por este INQUILINO"; //"PHONES EXTEN USED BY THIS TENANT";
$lang['go_list_id_tenant'] = "Identificadores de lista dentro ESTE INQUILINO"; //"LIST IDS WITHIN THIS TENANT";
$lang['go_campaign_id'] = "Identificación del CAMPAÑA"; //"CAMPAIGN ID";
$lang['go_campaign_name'] = "Nombre de la campaña"; //"CAMPAIGN NAME";
$lang['go_agent_name'] = "Nombre del agente"; //"AGENT NAME";
$lang['go_list_agents'] = "LISTA DE AGENTES"; //"LIST OF AGENTS";
$lang['go_make_sure_to_delete'] = "NOTA: Por favor, asegúrese de borrar el número de extensiones de teléfono que has deducido del inquilino afectado.";

// State Call Times
$lang['go_state_ct_wizard'] = "Asistente tiempos de llamada estado"; //"State Call Times Wizard";
$lang['go_add_new_state_ct'] = "Agregar nuevo Estado Tiempo de llamada"; //"Add New State Call Time";
$lang['go_state_ct_time_id'] = "Identificación del Estado Tiempo de llamada"; //"State Call Time ID";
$lang['go_state_ct_state'] = "Estado Estado Tiempo de llamada"; //"State Call Time State";
$lang['go_state_ct'] = "Nombre Estado Tiempo de llamada"; //"State Call Time Name";
$lang['go_state_ct_comments'] = "Comentarios estatales hora de llamada"; //"State Call Time Comments";

// [2] Call Times
$lang['go_call_times'] = "Tiempos de llamada"; //"Call Times";
$lang['go_call_times_s'] = "Tiempos de llamada"; //"call times";
$lang['go_ct_banner'] = "Tiempos de llamada"; //"Call Times";
$lang['go_state_call_times'] = "Tiempos de llamada Estado"; //"State Call Times";
$lang['go_state_call_not_avai'] = "Identificación del Estado llamada del tiempo no disponible."; //"State Call Time ID Not Available.";
$lang['go_state_Call_exist'] = "Un estado Identificación del tiempo de llamada ya existe."; //"A state call time id already exist.";
$lang['go_list_files_upload'] = "Lista de los archivos subidos"; //"List of Files Uploaded";
$lang['go_modify_inbound'] = "CAMBIAR DE ENTRADA"; //"MODIFY INBOUND";
$lang['go_call_time_id'] = "Identificación del tiempo de llamada"; //"Call Time ID";
$lang['go_call_time_name'] = "Llame Nombre Tiempo"; //"Call Time Name";
$lang['go_call_time_state'] = "Llame Estado Tiempo"; //"Call Time State";
$lang['go_default_start'] = "INICIO PREDETERMINADA"; //"DEFAULT START";
$lang['go_default_stop'] = "PARADA POR DEFECTO"; //"DEFAULT STOP";
$lang['go_group'] = "Grupo"; //"Group";
$lang['go_action'] = "Acción"; //"Action"; //
$lang['go_add_state_call_time'] = "Agregar nuevo Estado de llams"; //"Add New State Call Times";
$lang['go_call_times_wizard'] = "Tiempos de llamada asistente"; //"Call Times Wizard";
$lang['go_add_new_call_time'] = "Añadir Nuevo Tiempo de llamada"; //"Add New Call Time";
$lang['go_call_time_comments'] = "Llame Tiempo Comentarios"; //"Call Time Comments";
$lang['go_user_group'] = "Grupo de Usuarios"; //"User Group"; //
$lang['go_pls_sel_ug'] = "Por favor, seleccione un grupo de usuarios."; //"Please select a User Group.";
$lang['go_pls_sel_server'] = "Por favor, seleccione un servidor."; //"Please select a Server.";
$lang['go_del_con_server'] = "¿Seguro que quieres eliminar el servidor seleccionado"; //"Are you sure you want to delete the selected Server";
$lang['go_del_con_ug'] = "¿Seguro que quieres eliminar el grupo de usuarios seleccionado"; //"Are you sure you want to delete the selected User Group";
$lang['go_admin_exempt'] = "ADMINISTRADOR EXENTOS"; //"ADMIN EXEMPT";
$lang['go_multi_tenant'] = "Múltiples-Inquilino"; //"Multi-Tenant";
$lang['go_next'] = "Siguiente"; //"Next";
$lang['go_start'] = "COMIENZO"; //"START";
$lang['go_stop'] = "DETéNGASE"; //"STOP";
$lang['go_sun'] = "Domingo"; //"Sunday";
$lang['go_sat'] = "Sábado"; //"Saturday";
$lang['go_success_added_call_time_id'] = "";
$lang['go_success_s'] =  "Éxito"; //"Success!";
$lang['go_call_time_s'] = "Hora de llamada"; //"Call time";
$lang['go_has_been_modified'] = "ha sido modificado"; //"has been modified.";
$lang['go_state_call_time_s'] = "Tiempo de llamada Estado"; //"State call time";
$lang['go_state_call_time_rule_s'] = "Regla de tiempo de llamada Estado"; //"State call time rule";

//Modify Call time
$lang['go_pls_sel_call_time'] = "Por favor seleccione una llamada del tiempo."; //"Please select a Call Time.";
$lang['go_del_sel_ct'] = "¿Seguro que quieres eliminar la llamada del tiempo seleccionado"; //"Are you sure you want to delete the selected Call Time";
$lang['go_sel_ct_del'] = "Tiempos de llamada seleccionado se ha eliminado."; //"Selected call times has been deleted.";
$lang['go_pls_sel_state_call_time'] = "Por favor, seleccione un tiempo de llamada Estado."; //"Please select a State Call Time.";
$lang['go_del_sel_state_ct'] = "¿Seguro que quieres eliminar el estado de tiempo de llamada seleccionado"; //"Are you sure you want to delete the selected State Call Time";
$lang['go_sel_state_ct_del'] = "Tiempos de llamada estado seleccionado ha sido borrado."; //"Selected state call times has been deleted.";
$lang['go_modify'] = "MODIFICAR"; //"MODIFY";
$lang['go_call_time'] = "TIEMPO DE LLAMADA"; //"CALL TIME";
$lang['go_call_time_comments'] = "Llame Tiempo Comentarios"; //"Call Time Comments";
$lang['go_after_hours_audio'] = "FUERA DEL HORARIO DE AUDIO"; //"AFTER HOURS AUDIO";
$lang['go_default'] = "Defecto"; //"Default";
$lang['go_mon'] = "Lunes"; //"Monday";
$lang['go_tues'] = "Martes"; //"Tuesday";
$lang['go_wed'] = "Miércoles"; //"Wednesday";
$lang['go_thurs'] = "Jueves"; //"Thursday";
$lang['go_fri'] = "Viernes"; //"Friday";
$lang['go_active_state_call'] = "TIEMPO ACTIVO LLAMADA DEL ESTADO PARA EL REGISTRO"; //"ACTIVE STATE CALL TIME FOR THIS RECORD";
$lang['go_state_call_time_id'] = "IDENTIFICACIóN DEL ESTADO DE LA LLAMADA TIEMPO"; //"STATE CALL TIME ID";
$lang['go_state_call_defi'] = "ESTADO DE LLAMADA TIEMPO DEFINICIÓN"; //"STATE CALL TIME DEFINITION";
$lang['go_add_state_call_t_rule'] = "AÑADIR ESTADO TIEMPO DE LLAMADA REGLA"; //"ADD STATE CALL TIME RULE";
$lang['go_add_rule'] = "AGREGAR REGLA"; //"ADD RULE";
$lang['go_state_call_time_id'] = "Identificación del Estado Tiempo de llamada"; //"State Call Time ID";
$lang['go_state_Call_time_state'] = "Estado Estado Tiempo de llamada"; //"State Call Time State";
$lang['go_state_call_time_name'] = "Nombre Estado Tiempo de llamada"; //"State Call Time Name";
$lang['go_state_call_time_comments'] = "Comentarios estatales hora de llamada"; //"State Call Time Comments";
$lang['go_campaigns_using_call_time'] = "CAMPAÑAS DE USO DE ESTE TIEMPO DE LLAMADA"; //"CAMPAIGNS USING THIS CALL TIME";
$lang['go_inbound_groups_call_time'] = "GRUPOS DE ENTRADA UTILIZANDO ESTE TIEMPO DE LLAMADA"; //"INBOUND GROUPS USING THIS CALL TIME";
$lang['go_call_times_state'] = "LLAME TIEMPOS DE USAR ESTE TIEMPO DE LLAMADA DEL ESTADO"; //"CALL TIMES USING THIS STATE CALL TIME";
$lang['go_success'] = "Exito";// "Success";
$lang['go_success_caps'] = "EXITO"; //"SUCCESS!";// "éxito";
$lang['go_deld_ug'] = "Grupo de Usuarios eliminados"; //"Deleted User Group";
$lang['go_deld_Phone'] = "Teléfono Suprimido"; //"Deleted Phone";
$lang['go_deld_server'] = "Servidor Eliminado"; //"Deleted Server";
$lang['go_del_ug_uag_table'] = "Tabla del grupo de acceso de usuario Grupo de Usuarios eliminados"; //"Deleted User Group user_access_group table";


// [3] Carriers
$lang['go_carriers'] = "Transportistas"; //"Carriers";
$lang['go_carriers_server'] = "TRANSPORTISTAS EN ESTE SERVIDOR"; //"CARRIERS WITHIN THIS SERVER";
$lang['go_phones_server'] = "TELÉFONOS EN ESTE SERVIDOR"; //"PHONES WITHIN THIS SERVER";
$lang['go_confer_server'] = "CONFERENCIAS EN ESTE SERVIDOR"; //"CONFERENCES WITHIN THIS SERVER";
$lang['go_60_sec_effect'] = "Por favor espere hasta 60 segundos para que los cambios surtan efecto"; //"Please wait up to 60 seconds for the changes to take effect";
$lang['go_acc_already_exist'] ="Cuenta entrada ya existe para esta IP"; // "Account entry already exist for this IP";
$lang['go_add_new_carrier'] = "Añadir Nueva Compañía"; //"Add New Carrier";
$lang['go_carrier_id'] = "Identificación del Transportista"; //"Carrier ID";
$lang['go_carrier_name'] = "Nombre de la compañía"; //"Carrier Name";
$lang['go_server_ip'] = "Servidor IP"; //"Server IP"; //
$lang['go_protocol'] = "Protocolo"; //"Protocol";
$lang['go_registration'] = "Registro"; //"Registration";
$lang['go_status'] = "Estado"; //"Status"; //
$lang['go_web_login'] = "Web de inicio de sesión"; //"Web Login";
$lang['go_web_pass'] = "contraseña web"; //"Web Password";
$lang['go_sip_login'] = "SIP de inicio de sesión"; //"SIP Login";
$lang['go_sip_pass'] = "SIP Contraseña"; //"SIP Password";
$lang['go_voip_portal'] = "VoIP Portal";
$lang['go_login_here'] = "Iniciar sesión aquí"; //"Login Here";
$lang['go_no_records_found'] = "Ningún registro (s) encontrado"; //"No record(s) found";

//Modify Carrier
$lang['go_modify_carrier'] = "MODIFICAR TRANSPORTISTA"; //"MODIFY CARRIER";
$lang['go_modified_server'] = "MODIFICAR EL SERVIDOR"; //"MODIFY SERVER";
$lang['go_modified_phone'] = "MODIFICAR TELÉFONO"; //"MODIFY PHONE";
$lang['go_activated_exten'] = "Extensiones Activadas"; //"Activated Extensions(s)";
$lang['go_deact_exten'] = "Extensiones desactivado"; //"Deactivated Extensions(s)";
$lang['go_carrier_desc'] = "Descripción Portador"; //"Carrier Description";
$lang['go_reg_str'] = "Cadena de Registro"; //"Registration String";
$lang['go_account_entry'] = "Entrada cuenta"; //"Account Entry";
$lang['go_global_str'] = "Cadena Mundial"; //"Global String";
$lang['go_globals_str'] = "Cadena Globales"; //"Globals String";
$lang['go_dialplan_entry'] = "Entrada dialplan"; //"Dialplan Entry";
$lang['go_authentication'] = "Autenticación"; //"Authentication";
$lang['go_ip_based'] = "Basada en IP"; //"IP Based";
$lang['go_username'] = "Nombre de usuario"; //"Username";
$lang['go_server_ip_host'] = "Servidor IP / Host"; //"Server IP/Host";
$lang['go_port'] = "Puerto"; //"Port";
$lang['go_codecs'] = "Codecs";
$lang['go_dtmf_mode'] = "Modo DTMF"; //"DTMF Mode";
$lang['go_inband'] = "Banda"; //"Inband";
$lang['go_custom'] = "Costumbre"; //"Custom";
$lang['go_customs'] = "COSTUMBRE"; //"CUSTOM";
$lang['go_err_save_phone'] = "Error: Error en el teléfono ahorro"; //"Error: Error on saving phone";
$lang['go_success_save_phone'] = "Éxito: Teléfono guardados"; //"Success: Phone saved";
$lang['go_adv_conf'] = "ADVANCE CONFIGURACIÓN"; //"ADVANCE CONFIGURATION";



// Add New Carrier
$lang['go_carrier_wizard'] = "Asistente Portador"; //"Carrier Wizard";
$lang['_add_new_carrier'] = "Añadir Nueva Compañía"; //"Add New Carrier";
$lang['go_carrier_type'] = "tipo de Carrier"; //"Carrier type";


//Sign up
$lang['go_sign_up'] = "Contratar"; //"Signup";
$lang['go_required'] = "necesario"; //"Required";
$lang['go_fill_out'] = "Por favor complete la información de abajo"; //"Please fill out the information below";
$lang['go_company'] = "Empresa"; //"Company";
$lang['go_address'] = "Dirección"; //"Address";
$lang['go_city'] = "Ciudad"; //"City";
$lang['go_state'] = "Estado"; //"State";
$lang['go_postal_code'] = "Código Postal"; //"Postal Code";
$lang['go_country'] = "País"; //"Country";
$lang['go_time_zone'] = "Huso Horario"; //"Time Zone";
$lang['go_mobile_phone'] = "Teléfono Móvil"; //"Mobile Phone";

//Term And Condition
$lang['go_terms_and_condition'] = "Términos y Condiciones"; //"Terms and Condition";
$lang['go_tc1'] = "Este sitio es propiedad y está operado por"; //"This site is owned and operated by";
$lang['go_tc2'] = '("nosotros", "nos", "nuestro" o'; //'("we", "us", "our" or';
$lang['go_tc3'] = 'ofrece sus servicios a usted (el "Cliente", "usted" o "usuario final")
sujeto a las siguientes condiciones'; //'provides its services to you ("Customer", "you" or "end user") 
		   //subject to the following conditions';
$lang['go_tc4'] = "Si usted visita o compra en nuestro sitio web o cualquier otro afiliado"; //"If you visit or shop at our website or any other affiliated";
$lang['go_tc5'] = "teléfono de búsqueda inversa"; //"reverse phone lookup";
$lang['go_tc6'] = "sitios web,
usted afirmativamente acepta las siguientes condiciones.
El uso continuado del sitio y de cualquiera de"; //"websites,
                   //you affirmatively accept the following conditions.
                   //Continued use of the site and any of";
$lang['go_tc7'] = "servicios constituye él acuerdo afirmativo a estos términos y condiciones."; //"services constitutes
                   //the affirmative agreement to these terms and conditions.";
$lang['go_tc8'] = "se reserva el derecho de modificar los términos, condiciones y avisos bajo los cuales"; //"reserves the right to change the terms, conditions and notices under which the";
$lang['go_tc9'] = "sitios y servicios se ofrecen, incluyendo pero no limitado a, los cargos asociados con el uso de la"; //"sites and services are offered,including but not limited to the charges associated with the use of the";
$lang['go_tc10'] = "sitios y servicios."; //"sites and services.";
$lang['go_tc11'] = "1. Comunicaciones Electrónicas"; //"1. Electronic Communications";
$lang['go_tc12'] = "1.1. Cuando usted visita"; //"1.1. When you visit";
$lang['go_tc13'] = "sitios web o enviar correo electrónico a nosotros, usted se está comunicando con nosotros electrónicamente. Usted da su consentimiento para recibir comunicaciones de nosotros electrónicamente. Nos comunicaremos con usted por correo electrónico o mediante la publicación de avisos en este sitio. Usted acepta que todos los acuerdos, avisos, divulgaciones y otras comunicaciones que le proporcionamos electrónicamente satisfacen cualquier requisito legal que dichas comunicaciones sean por escrito"; //"websites or send Email to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by Email or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing";
$lang['go_tc14'] = "2. Las marcas comerciales y derechos de autor"; //"2. Trademarks and Copyright";
$lang['go_tc144'] = "2.1. Todo el contenido de este sitio, como texto, gráficos, logotipos, iconos de botones, imágenes, marcas o derechos de autor son propiedad de sus respectivos dueños. Nada en este sitio debe ser interpretado como una concesión de cualquier licencia o derecho a usar cualquier marca registrada sin la autorización escrita de su titular."; //"2.1. All content on this site, such as text, graphics, logos, button icons, images, trademarks or copyrights are the property of their respective owners. Nothing in this site should be construed as granting any right or license to use any Trademark without the written permission of its owner.";
$lang['go_tc15'] = "3. Servicios y condiciones"; //"3. Services & Conditions";
$lang['go_tc16'] = "no se hace responsable de cualquier retraso o falta de prestación del servicio (s) en cualquier momento. En ningún caso,"; //"shall not be held liable for any delay or failure to provide service(s) at any time. In no event shall";
$lang['go_tc17'] = "sus funcionarios, directores, empleados, accionistas, afiliados, uno de los caballeros o proveedores que suministre servicios al cliente en conexión con este acuerdo o el servicio será responsable de ningún daño directo, incidente, indirectos, especiales, punitivos, ejemplares o daños consecuentes, incluyendo pero no limitado a la pérdida de datos, pérdida de ingresos, beneficios o ganancias anticipadas, o daños que surjan de o en conexión con el uso o la imposibilidad de usar el servicio. Las limitaciones establecidas en este documento se aplican al tiempo reclamado fundada en incumplimiento de contrato, incumplimiento de garantía, responsabilidad por el producto y cualquier y toda otra responsabilidad y aplicar o no"; //"its officers, Directors, Employees, Shareholders, Affiliates, Agents or Providers who furnishes services to customer in connection with this agreement or the service be liable for any direct, incident, indirect, special, punitive, exemplary or consequential damages, including but not limited to loss of data, lost of revenue, profits or anticipated profits, or damages arising out of or in connection to the use or inability to use the service. The limitations set forth herein apply to the claimed founded in Breach of Contract, Breach of Warranty, Product Liability and any and all other liability and apply weather or not";
$lang['go_tc18'] = "fue informado de la campana probable de cualquier tipo particular de daño."; //"was informed of the likely hood of any particular type of damage.";
$lang['go_tc19'] = "no ofrece garantías de ningún tipo, escrita o implícita, sobre el servicio en el que se ofrece."; //"makes no warranties of any kind, written or implied, to the service in which it provides.";
$lang['go_tc200'] = "ofrece sólo servicios de prepago. Usted debe mantener un saldo positivo para retener los servicios con"; //"provides prepaid services only. You must keep a positive balance to retain services with";
$lang['go_tc20'] = "Usted debe pagar todos los saldos negativos de inmediato. Cliente se compromete a mantener un saldo positivo en la cuenta del cliente en todo momento y se compromete a pagar la tasa en la que el cliente se inscribió en cualquier destino. El cliente acepta pagar cualquier y todos los cargos que el cliente incurre durante el uso"; //"You must pay all negative balances immediately. Customer agrees to keep a positive balance in customer's account at all times and agrees to pay the rate in which the customer signed up for any destinations. Customer agrees to pay any and all charges that customer incurs while using";
$lang['go_tc21'] = "servicio."; //"service.";
$lang['go_tc22'] = "Servicios de VOIP y la nube no están destinados para su uso como fuente de teléfono principal para los usuarios empresariales o residenciales."; //"VOIP and Cloud services are not intended for use as a primary telephone source for business or residential users.";
$lang['go_tc23'] = "no cuenta con servicio e911."; //"does not provide e911 service.";
$lang['go_tc24'] = "Todas las llamadas realizadas a través"; //"All calls placed through";
$lang['go_tc255'] = "Red VOIP a US48 destinos se facturan a los 6 mentos segundo INCRE menos que se indique lo contrario."; //"VOIP network to US48 destinations are billed at 6 second increments unless otherwise stated.";
$lang['go_tc25'] = "El Cliente acepta la jurisdicción exclusiva de los tribunales de Pasig City en la República de las Filipinas para cualquier y todos los asuntos legales."; //"Customer agrees to the exclusive jurisdiction of the courts of Pasig City in the Republic of the Philippines for any and all legal matters.";
$lang['go_tc26'] = "La violación de cualquier ley o leyes estatales o federales para cualquier otro fuero mpetent co puede resultar en la terminación inmediata de la cuenta y / o la desconexión del servicio infractor."; //"Violation of any state or federal laws or laws for any other competent jurisdiction may result in immediate account termination and/or disconnection of the offending service.";
$lang['go_tc27'] = "se reserva el derecho de suspender el servicio en cualquier momento, con o aviso Hout ingenio; especialmente si se encuentra al Cliente para estar en violación de"; //"reserves the right to terminate service at any time with or without notice; especially if Customer is found to be in violation of";
$lang['go_tc28'] = "Términos y condiciones. Usted acepta que"; //"Terms & Conditions. You agree that";
$lang['go_tc29'] = "Debido a la naturaleza de esta industria y la tasa de fraude de tarjetas de crédito alta,"; //"Due to the nature of this industry and high credit card fraud rate,";
$lang['go_tc30'] = "se reserva el derecho de solicitar la siguiente documentación a efectos de verificación; Una copia de la tarjeta de crédito utilizada para establecer la cuenta junto con una identificación oficial, como un pasaporte, permiso de conducir u otra identificación emitida por el gobierno."; //"reserves the right to request the following documentation for verification purposes; A copy of the credit card used to establish the account along with valid photo identification such as a Passport, Drivers License or other Government issued identification.";
$lang['go_tc31'] = "DID y TFN (Números sin costo) Servicios y Suscripciones de activación y desactivación"; //"DID and TFN (Toll Free Numbers ) Services and Subscriptions Activation and Deactivation";
$lang['go_tc32'] = "DID / TFN abono mensual se deducirá o debitado del saldo de la cuenta del cliente o créditos, con o sin previo aviso de forma automática; antes de la activación del servicio de su acuerdo de suscripciones."; //"DID/TFN monthly service fee shall be automatically deducted or debited from the customer's account balance or credits with or without prior notice; prior to activation of service its subscriptions agreement.";
$lang['go_tc33'] = "Débito automático del pago mensual se iniciará una vez DID / TFN ha sido activada."; //"Auto-debit of monthly payment shall commence once DID/TFN has been activated.";
$lang['go_tc34'] = "La falta de pago acordado DID / TFN servicios mensuales y la cuota de suscripción mensual (que tienen un [1] mes factura sin pagar), estarán sujetos a DID desactivación / TFN"; //"Failure to pay the agreed DID/TFN monthly services and monthly subscription fee (having one [1] month unpaid bill) shall be subject to DID/TFN deactivation.";
$lang['go_tc35'] = "Un período máximo de un 1 mes de gracia se le entrega al cliente para resolver su cuenta / antes DID / desactivación TFN y / o eliminación."; //"A maximum one 1 month grace period shall be given to the customer to settle his/her account before DID/TFN deactivation and/or deletion.";
$lang['go_tc36'] = "Apoyo Técnico"; //"Technical Support";
$lang['go_tc37'] = "Soporte técnico está disponible de lunes a viernes de 09:00 a 24:00 EST 24/5, todas las preocupaciones de apoyo deben ser presentadas en"; //"Technical Support is available Mondays to Fridays 09:00 to 24:00 24/5 EST, all support concerns should be filed at";
$lang['go_tc38'] = "sistema de venta de entradas"; //"ticketing system";
$lang['go_tc39'] = "Soporte Técnico Mensual"; //"Monthly Technical Support";
$lang['go_tc40'] = "suscripciones mensuales de apoyo cubre las configuraciones y reparación para las siguientes cuestiones:"; //"monthly support subscriptions covers the configurations and troubleshooting for the following issues:";
$lang['go_tc41'] = "Campañas - saliente, creación de campañas entrantes y mezclado y configuraciones
                                                                             Listas / Leads - creación de listas y la carga de clientes potenciales.
                                                                             Los estados / configuración Disposiciones
                                                                             Llame configuración Tiempos
                                                                             IVR - Configuración básica (un solo nivel)
                                                                             Tutorial básico para la Campaña y Conductores de gestión."; //"Campaigns – outbound, inbound and blended campaign creation and configurations
                                                                          //  Lists/Leads – creation of lists and loading of leads.
                                                                           // Statuses/Dispositions configuration
                                                                           // Call Times configuration
                                                                           // IVR – Basic configuration (one level only)
                                                                           // Basic tutorial for Campaign and Leads management.";
$lang['go_tc42'] = "Todas las configuraciones de avance no mencionados anteriormente serán cargadas con la tasa de apoyo regular por hora de $ 80 por hora."; //"All advance configurations not listed above will be charged with the regular hourly support rate of $80 per hour.";
$lang['go_tc43'] = "Proporcionamos apoyo limitado y proporcionamos configuraciones muestras para IP Telefonía / Softphones. Es responsabilidad de los usuarios finales configurar correctamente sus estaciones de trabajo y dispositivos para su uso con"; //"We provide limited support and provide samples configurations for IP Phones/Softphones. It is the end users responsibility to properly configure their workstations and devices for use with";
$lang['go_tc44'] = "servicios."; //"services.";
$lang['go_tc45'] = "Lidera la gestión, gestión de campañas, el seguimiento y la generación de informes de agente son responsabilidad de los usuarios finales."; //"Leads Management, Campaign Management, Agent Monitoring and Reports Generation are end users responsibility.";
$lang['go_tc46'] = "Soporte Técnico de Emergencia"; //"Emergency Technical Support";
$lang['go_tc47'] = "Apoyo técnico de emergencia fuera de la cobertura regular de lunes a viernes de 9:00 a 24:00 EST se le cobrará $ 80 por hora."; //"Emergency technical support outside the regular coverage of Monday to Friday 9:00 to 24:00 EST will be charged $80 per hour.";
$lang['go_tc48'] = "Apoyo técnico de emergencia para fin de semana y días de fiesta se le cobrará $ 160 por hora."; //"Emergency technical support for Weekend and Holidays will be charged $160 per hour.";
$lang['go_tc49'] = "Política de Reembolso"; //"Refund Policy";
$lang['go_tc50'] = "VoIP y servicios en la nube: Ofrecemos reembolsos completos sobre saldo de prepago de servicios de VoIP y de nube a petición de todos los pagos realizados dentro de los 7 días."; //"VoIP and Cloud Services: We offer full refunds on remaining pre-paid balance on VoIP and Cloud services upon request for all payments made within 7 days.";
$lang['go_tc51'] = "Suscripciones mensuales: no cuenta con reembolsos por suscripciones mensuales como marcador alojado, DID o números de teléfono gratuitos"; //"Monthly Subscriptions: We do not offer refunds for monthly subscriptions such as Hosted Dialer, DID's or Toll Free numbers";
$lang['go_tc52'] = "Prepago Soporte Técnico y Servicios de Consultoría: Ofrecemos reembolsos sólo si hay apoyo o servicio de consultoría técnica y ha sido prestados."; //"Prepaid Technical Support and Consulting Services: We offer refunds only if no technical support or consulting service and has been rendered.";
$lang['go_tc53'] = "No habrá reembolsos por una sola vez / tarifas de establecimiento"; //"There will be no refunds for one-time/setup fees";
$lang['go_tc54'] = "Políticas del Sitio, Modificación y Divisibilidad"; //"Site Policies, Modification & Severability";
$lang['go_tc55'] = "Nos reservamos el derecho de hacer cambios a nuestro sitio, políticas, y estos Términos y Condiciones en cualquier momento. Si alguna de estas condiciones se considerara inválida, nula o por cualquier razón inaplicable, esta condición se considerará separable y no afectará la validez y aplicabilidad de ninguna condición restante."; //"We reserve the right to make changes to our site, policies, and these Terms & Conditions at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition.";
$lang['go_tc56'] = "Quejas generales"; //"General Complaints";
$lang['go_tc57'] = "Por favor, envíe los informes de actividad en violación de estos Términos y Condiciones a"; //"Please send reports of activity in violation of these Terms & Conditions to";
$lang['go_tc58'] = "investigará razonablemente hechos relacionados con dichas violaciónes."; //"will reasonably investigate incidents involving such violations.";
$lang['go_tc59'] = "puede implicar y cooperarán con los funcionarios encargados de hacer cumplir la ley si se sospecha de alguna actividad criminal. Violaciónes pueden implicar responsabilidad penal y civil"; //"may involve and will cooperate with law enforcement officials if any criminal activity is suspected. Violations may involve criminal and civil liability";
$lang['go_tc60'] = "los pagos de PayPal"; //"Paypal Payments";
$lang['go_tc61'] = "En caso de pago a través de PayPal.com, cliente entiende plenamente que no habrá envío producto tangible a cualquier dirección. El cliente entiende que están comprando servicios para los que"; //"In case of payment via PayPal.com, customer fully understands that there will be no tangible product shipping to any address. The customer understands that they are purchasing services for which";
$lang['go_tc62'] = "proporciona el historial de llamadas en línea (CDR) para el uso de VOIP / Cloud y / o salientes / entrantes informes para el Marcador. En caso de PayPal disputa el cliente se compromete a respetar"; //"provides online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for the Dialer. In case of PayPal disputes the customer agrees to abide by";
$lang['go_tc63'] = "en línea el historial de llamadas (CDR) para VOIP uso / Cloud y / o salientes / entrantes informes para el servicio entregado por un total del pago PayPal.com."; //"online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for delivered service totaling the PayPal.com payment.";
$lang['go_tc64'] = "Limitación de responsabilidades"; //"Limitation of Liabilities";
$lang['go_tc65'] = "En ningún caso"; //"In no event shall";
$lang['go_tc66'] = "será responsable ante ninguna parte por cualquier daño directo, indirecto, incidental, especial, ejemplar o consecuente de cualquier tipo que sean relacionados con o que surjan de este sitio web o cualquier uso de este sitio web, o de cualquier sitio o recurso relacionado con, referencia, o el acceso a través de este sitio web, o por el uso o descarga de, o el acceso a cualquier material, información, productos o servicios, incluyendo, sin limitación, cualquier pérdida de beneficios, interrupción del negocio, losahorro camisetas o pérdida de programas u otros datos, incluso si"; //"be liable to any party for any direct, indirect, incidental, special, exemplary or consequential damages of any type whatsoever related to or arising from this website or any use of this website, or any site or resource linked to, referenced, or access throught this website, or for the use or downloading of, or access to, any materials, information, products, or services, including withouth limitation, any lost profits, business interruption, lost savings or loss of programs or other data, even if";
$lang['go_tc67'] = "Se advierte expresamente de la posibilidad de tales daños."; //"is expressly advised of the possiblity of such damages.";
$lang['go_tc68'] = "Cumplimiento de llamadas"; //"Call Compliance";
$lang['go_tc69'] = "tiene pleno cumplimiento regulatorio EE.UU., Reino Unido y Canadá. El Cliente entiende plenamente que es su responsabilidad seguir estas regulaciones. El no hacerlo puede resultar en la suspensión inmediata de la cuenta y / o la desconexión."; //"has full USA, UK and Canada regulatory compliance. Customer fully understands that it is their responsibility to follow these regulations. Failure to do so may result in immediate account suspension and/or disconnection.";
$lang['go_tc70'] = "Estoy de acuerdo con la"; //"I agree to the";
$lang['go_required'] = "Necesario"; //"Required";
$lang['go_valid_email'] = "Introduce dirección de correo electrónico válida"; //"Enter valid email";
$lang['go_max_12_char'] = "Máximo de 12 caracteres"; //"Maximum of 12 character";
$lang['go_tc71'] = "No legible? Cambie el texto."; //"Not readable? Change text.";
$lang['go_tc72'] = "Introduzca el código de la imagen por encima de aquí."; //"Enter code from above picture here.";
$lang['go_err_long'] = "Error: la inscripción Sippy demasiado largo"; //"Error: Sippy registration too long";
$lang['go_space'] = "El espacio no está permitido"; //"Space are not allowed";
$lang['go_min_3_char'] = "Un mínimo de 3 caracteres."; //"Minimum of 3 characters.";
$lang['go_min_2_char'] = "Un mínimo de 2 caracteres."; //"Minimum of 2 characters.";
$lang['go_src_carrier'] = "Carrier fuente"; //"Source Carrier";
$lang['go_accept_tc'] = "Debe aceptar los términos y condiciones"; //"You must accept the terms and conditions";
$lang['go_welcome_to'] = "Bienvenido a"; //"Welcome to";
$lang['go_ccc'] = "Centro de llamadas nube"; //"Cloud Call Center";
$lang['go_w1'] = "es una herramienta fácil de configurar y fácil de usar, hágalo usted mismo la solución de telefonía basado en la nube (DIY) para cualquier tipo de organización en cualquier lugar del país que realice sus actividades de ventas, comercialización, servicio y soporte. Diseñado para empresas de call center grande de nivel empresarial pero razonable para ajustarse al presupuesto del pequeño empresario,"; //"is an easy to set up and easy to use, do it yourself (DIY) cloud based telephony solution for any type of organization in wherever country you conduct your sales, marketing, service and support activites. Designed for large enterprise-grade call center companies but priced to fit the budget of the Small Business Owner,";
$lang['go_w2'] = "utiliza las interfaces gráficas de usuario intuitivas para que la implementación es rápida y sin complicaciones, entre sus docenas de características calientes."; //"uses intuitive graphical user interfaces so that deployment is quick and hassle-free, among its dozens of hot features.";
$lang['go_w3'] = "El uso de las infraestructuras cloud seguro certificado por las normas internacionales,"; //"Using secure cloud infrastructures certified by international standards,";
$lang['go_w4'] = 'es un "uso en cualquier lugar, en cualquier momento" aplicación web para que pueda crear más clientes para la vida - en la oficina, en casa o en la playa.'; //'is a "Use Anywhere, Anytime" web app so that you can create more customers for life – in the office, at home or at the beach.';
$lang['go_email'] = "Correo electrónico"; //"email";
$lang['go_w5'] = "para obtener 120 minutos gratis (EE.UU., Reino Unido y Canadá sólo llamadas)."; //"to get 120 free minutes (US, UK and Canada calls only).";

// [4] Phones
$lang['go_exten'] = "Extensión"; //"Extension";
$lang['go_exten_only'] = "Exten";
$lang['go_server'] = "Servidor"; //"Server";
$lang['go_dial_plan'] = "Plan de marcación"; //"Dial Plan";
$lang['go_dial_prefix'] = "Marcar Prefijo"; //"Dial Prefix";
$lang['go_del_selected'] = "Eliminar Seleccionados"; //"Delete Selected"; //
$lang['go_num_only'] = "sólo numérico"; //"numeric only";
$lang['go_voicemail_box'] = "Buzón de voz"; //"Voicemail Box";
$lang['go_vm_box_navailable'] = "Buzón de voz no está disponible."; //"Voicemail Box Not Available.";
$lang['go_add_new_phone'] = "Añadir nuevo teléfono"; //"Add New Phone";

//Add New Phone
$lang['go_phone_wizard'] = "Asistente de teléfono"; //"Phone Wizard";
$lang['go_add_new_phone'] = "Añadir nuevo teléfono"; //"Add New Phone";
$lang['go_additional_phone'] = "Otro teléfono(s)"; //"Additional Phone(s)";
$lang['go_starting_exten'] = "A partir de Extensión"; //"Starting Extension";
$lang['go_client_protocol'] = "Protocolo de Cliente"; //"Client Protocol";
$lang['go_external'] = "EXTERNO"; //"EXTERNAL";
$lang['go_tenants'] = "inquilinos"; //"tenants";
$lang['go_phone_exten_login'] = "Extensión Teléfono / Entrar"; //"Phone Extension/Login";
$lang['go_phone_login_pass'] = "Teléfono de sesión Contraseña"; //"Phone Login Password";
$lang['go_phone_reg_password'] = "Teléfono Registro Contraseña"; //"Phone Registration Password";
$lang['go_dial_prefix'] = "Marcar Prefijo"; //"Dial Prefix";
$lang['go_all_user_groups'] = "Todos los grupos de usuarios"; //"All User Groups"; //
$lang['go_sip_server'] = "Servidor SIP"; //"SIP Server";
$lang['go_local_gmt'] = "Local GMT";
$lang['go_do_not_adjust_DST'] = "NO ajuste para el horario de verano"; //"Do NOT adjust for DST";

//Modify Phone
$lang['go_modify_phone'] = "MODIFICAR TELÉFONO"; //"MODIFY PHONE";
$lang['go_del_phone'] = "BORRAR TELÉFONO"; //"DELETE PHONE";
$lang['go_suspended'] = "SUSPENDIDO"; //"SUSPENDED";
$lang['go_closed'] = "CERRADO"; //"CLOSED";
$lang['go_pending'] = "PENDIENTE"; //"PENDING";
$lang['goexternal'] = "EXTERNO"; //"EXTERNAL";
$lang['go_pls_sel_tenant'] = "Seleccione un inquilino."; //"Please select a Tenant.";
$lang['go_phone_ext_limit'] = "ADVERTENCIA: Extensión de teléfono está sobre el límite"; //"WARNING: Phone Extension is over the limit";
$lang['go_pls_support_assistance'] = "Por favor, póngase en contacto con nuestro soporte para la asistencia"; //"Please contact our Support for assistance.";
$lang['go_phone_exten_limit'] = "ADVERTENCIA: Extensión de teléfono está sobre el límite de"; //"WARNING: Phone Extension is over the limit of";
$lang['go_exten_exist'] = "ADVERTENCIA: Una o más extensiones de la gama dado ya existe."; //"WARNING: One or more extensions from the range given are already exist.";
$lang['go_phone_ext_login'] = "Extensión Teléfono / Entrar"; //"Phone Extension/Login";
$lang['go_phone_pass'] = "Contraseña del teléfono"; //"Phone Password";
$lang['go_pass'] = "Contraseña"; //"Password";
$lang['go_dial_plan_num'] = "Marcar número Plan de"; //"Dial Plan Number";
$lang['go_outbound_callerid'] = "ID de llamada saliente"; //"Outbound Caller ID";
$lang['go_phone_ip_address'] = "Teléfono Dirección IP"; //"Phone IP Address";
$lang['go_comp_ip_address'] = "Dirección IP del ordenador"; //"Computer IP Address";
$lang['go_agent_screen_login'] = "Agente de la pantalla Inicio de sesión"; //"Agent Screen Login";
$lang['go_sip_reg_pass'] = "SIP Registro Contraseña"; //"SIP Registration Password";
$lang['go_active'] = "ACTIVO"; //"ACTIVE"; 
$lang['go_inactive'] = "INACTIVO"; //"INACTIVE";
$lang['go_set_webphone'] = "Establecer como teléfono Web"; //"Set As Webphone";
$lang['go_webphone_dialpad'] = "Establecer como teléfono Web"; //"Teléfono Web Teclado telefónico"; //"Webphone Dialpad";
$lang['go_use_external_server_ip'] = "Uso externo IP del servidor"; //"Use External Server IP";
$lang['go_active_account'] = "Cuenta Activa"; //"Active Account";
$lang['go_phone_type'] = "Tipo de teléfono"; //"Phone Type";
$lang['go_company'] = "Empresa"; //"Company";
$lang['go_picture'] = "Imagen"; //"Picture";
$lang['go_new_msg'] = "Nuevo Mensaje"; //"New Messages";
$lang['go_old_msg'] = "Mensaje Histórico"; //"Old Messages";
$lang['go_phone_ring_timeout'] = "Teléfono Anillo Tiempo de espera"; //"Phone Ring Timeout";
$lang['go_onhook_agent'] = "Colgado Agente"; //"On-Hook Agent";
$lang['go_default_user'] = "Usuario Predeterminado"; //"Default User";
$lang['go_default_pass'] = "Contraseña por defecto"; //"Default Pass";
$lang['go_default_campaign'] = "Campaña por defecto"; //"Default Campaign";
$lang['go_park_exten'] = "Exten el Parque"; //"Park Exten";
$lang['go_conf_exten'] = "Conf Exten";
$lang['go_monitor_prefix'] = "Monitor de Prepix"; //"Monitor Prefix";
$lang['go_recording_exten'] = "Grabación Exten"; //"Recording Exten";
$lang['go_voicemail_exten'] = "Correo de voz exten"; //"Voicemail Exten";
$lang['go_voicemail_dump_exten'] = "Correo de voz volcado exten"; //"Voicemail Dump Exten";
$lang['go_exten_context'] = "Contexto exten"; //"Exten Context";
$lang['go_phone_context'] = "Contexto Teléfono"; //"Phone Context";
$lang['go_call_logging'] = "Registro de llamadas"; //"Call Logging";
$lang['go_user_switching'] = "Cambio de usuario"; //"User Switching";
$lang['go_conferencing'] = "Conferencias"; //"Conferencing";
$lang['go_admin_hang_up'] = "Admin Colgar"; //"Admin Hang Up";
$lang['go_admin_hijack'] = "Admin Secuestro"; //"Admin Hijack";
$lang['go_admin_monitor'] = "Monitor de administración"; //"Admin Monitor";
$lang['go_call_park'] = "Aparcado de llamadas"; //"Call Park";
$lang['go_updater_check'] = "Comprobar Updater"; //"Updater Check";
$lang['go_af_logging'] = "Registro de AF"; //"AF Logging";
$lang['go_queue_enabled'] = "Cola Activado"; //"Queue Enabled";
$lang['go_callerid_popup'] = "Identificador de llamadas emergente"; //"CallerID Popup";
$lang['go_voicemail_button'] = "Botón del correo de voz"; //"Voicemail Button";
$lang['go_fast_refresh'] = "Actualizar rápido"; //"Fast Refresh";
$lang['go_fast_refresh_rate'] = "Rápido Frecuencia de actualización"; //"Fast Refresh Rate";
$lang['go_persistant_mysql'] =  "Persistente MySQL"; //"Persistant MySQL";
$lang['go_auto_dial_next_num'] = "Marcación automática próximo número"; // "Auto Dial Next Number";
$lang['go_stop_recording_after_each_call'] = "Deje de grabación después de cada llamada"; //"Stop Recording After Each Call";
$lang['go_enable_sipsak_msg'] = "Habilitar SIPSAK Mensajes"; //"Enable SIPSAK Messages";
$lang['go_template_id'] = "Identificación del"; //"Template ID";
$lang['go_conf_override'] = "Ignorar Conf"; //"Conf Override";

// [5] Servers
$lang['go_servers'] = "Servidores"; //"Servers";
$lang['go_servers_s'] = "Servidores"; //"servers";
$lang['go_server_id'] = "Identificación del servidor"; //"Server ID";
$lang['go_server_id_navailable'] = "Identificación del servidor no está disponible."; //"Server ID Not Available.";
$lang['go_server_banner'] = "Servidores"; //"Servers";
$lang['go_server_entry_del'] = "SERVIDOR DE ENTRADA"; //"SERVER ENTRY";
$lang['go_modify_server'] = "MODIFICAR EL SERVIDOR"; //"MODIFY SERVER";
$lang['go_del_servers'] = "BORRAR EL SERVIDOR"; //"DELETE SERVER";
$lang['go_asterisk'] = "Asterisco"; //"Asterisk";
$lang['go_trunks'] = "Troncos" ; //"Trunks";
$lang['go_add_trunks'] = "Añadir troncos"; //"Add Trunks";
$lang['go_gmt'] = "GMT";
$lang['go_add_new_server'] = "Añadir nuevo servidor"; //"Add New Server";
$lang['go_vmail'] = "VMAIL";
$lang['go_search'] = "Búsqueda"; //"Search";
$lang['go_show_list'] = "Mostrar la lista"; //"showList";



//Add New Server
$lang['go_server_wizard'] = "Asistente del servidor"; //"Server Wizard";
$lang['add_new_server'] = "Añadir nuevo servidor"; //"Add New Server";
$lang['go_available'] = "Disponible"; //"Available";
$lang['go_act_vm'] = "Los mensajes de voz activado:"; //"Activated Voicemail(s):";
$lang['go_act_server'] = "Servidores Activado"; //"Activated Server(s)";
$lang['go_deact_vm'] = "Correo de voz(s) Desactivado"; //"Deactivated Voicemail(s):";
$lang['go_deact_server'] = "Servidor Desactivado(s)"; //"Deactivated Server(s)";
$lang['go_deld_server'] = "Servidor eliminado(s)"; //"Deleted Server(s)";
$lang['go_not_available'] = "No Disponible"; //"Not Available";
$lang['go_err_phone'] = "Error: Teléfono"; //"Error: Phone";
$lang['go_server_desc'] = "Descripción del servidor"; //"Server Description";
$lang['go_asterisk'] = "ASTERISCO"; //"ASTERISK";
$lang['go_asterisk_version'] = "Versión Asterisco"; //"Asterisk Version";
$lang['go_ok'] = "Okay";
$lang['go_clear_search'] = "Borrar la búsqueda"; //"Clear Search";

//Modify Server
$lang['go_modify_server'] = "Modificar servidor"; //"Modify Server";
$lang['go_system_load'] = "Carga del sistema"; //"System Load";
$lang['go_live_channels'] = "Canales en Vivo"; //"Live Channels";
$lang['go_disk_usage'] = "Uso del disco"; //"Disk Usage";
$lang['go_admin_user_group'] = "Grupo de usuarios de administración"; //"Admin User Group";
$lang['go_max_trunks'] = "Troncos Máximos"; //"Max Trunks";
$lang['go_max_call_per_second'] = "Llamada máxima por segundo"; //"Max Call per Second";
$lang['go_bal_dialing'] = "Marcación equilibrio"; //"Balance Dialing";
$lang['go_bal_rank'] = "Equilibrio Rango"; //"Balance Rank";
$lang['go_bal_offlimits'] = "Equilibrio Offlimits"; //"Balance Offlimits";
$lang['go_telnet_host'] = "Anfitrión Telnet"; //"Telnet Host";
$lang['go_telnet_port'] = "Telnet Puerto"; //"Telnet Port";
$lang['go_manager_user'] = "Usuario Administrador"; //"Manager User";
$lang['go_manager_secret'] = "Gerento Secreto"; //"Manager Secret";
$lang['go_manager_update_user'] = "Gerente de usuario de actualización"; //"Manager Update User:";
$lang['go_manager_listen_user'] = "Gerente Escuchar usuario"; //"Manager Listen User";
$lang['go_manager_send_user'] = "Gerente Enviar Usuario"; //"Manager Send User";
$lang['go_conf_file_secret'] = "Secreto Conf Archivo"; //"Conf File Secret";
$lang['go_weak'] = "Débil"; //"Weak";
$lang['go_medium'] = "Medio"; //"Medium";
$lang['go_strong'] = "Fuerte"; //"Strong";
$lang['go_local_gmt'] = "Local GMT";
$lang['go_voicemal_dump_exten'] = "Buzón de voz Dump Exten"; //"Voicemail Dump Exten";
$lang['go_autodial_exten'] = "Autodial Extensión"; //"Autodial Extension";
$lang['go_default_context'] = "Por defecto Contexto"; //"Default Context";
$lang['go_system_performance_log'] = "Sistema de registro de rendimiento"; //"System Performance Log";
$lang['go_server_logs'] = "Registros del Servidor"; //"Server Logs";
$lang['go_agi_output'] = "AGI salida"; //"AGI Output";
$lang['go_carrier_logging_active'] = "Registro Carrier Activo"; //"Carrier Logging Active";
$lang['go_recording_web_link'] = "Grabación de enlace"; //"Recording Web Link";
$lang['go_alternate_recording_server_ip'] = "Suplente Recording Server IP"; //"Alternate Recording Server IP";
$lang['go_external_server_ip'] = "IP con servidor externo"; //"External Server IP";
$lang['go_active_twin_server_ip'] = "Activo IP del servidor gemelo"; //"Active Twin Server IP";
$lang['go_active_asterisk_server'] = "Servidor activo Asterisk"; //"Active Asterisk Server";
$lang['go_active_agent_server'] = "Servidor activo Agente"; //"Active Agent Server";
$lang['go_generate_conf_files'] = "Generar Conf Archivos"; //"Generate Conf Files";
$lang['go_rebuild_conf_files'] = "Reconstruir archivos conf"; //"Rebuild conf Files";
$lang['go_rebuild_moh'] = "Reconstruye Música en espera"; //"Rebuild Music On Hold";
$lang['go_sounds_update'] = "Suena Actualización"; //"Sounds Update";
$lang['go_recording_limit'] = "Límite de grabación"; //"Recording Limit";
$lang['go_custom_dialplan_entry'] = "Entrada Dialplan personalizada"; //"Custom Dialplan Entry";
$lang['go_trunks_this_server'] = "TRONCOS PARA ESTE SERVIDOR"; //"TRUNKS FOR THIS SERVER";
$lang['go_add_new_server_tr'] = "AÑADIR UN NUEVO RÉCORD SERVIDOR TRONCO"; //"ADD NEW SERVER TRUNK RECORD";
$lang['go_restriction'] = "restricción"; //"Restriction";
$lang['go_carriers_within_server'] = "TRANSPORTISTAS EN ESTE SERVIDOR"; //"CARRIERS WITHIN THIS SERVER";
$lang['go_phones_server'] = "TELÉFONOS EN ESTE SERVIDOR"; //"PHONES WITHIN THIS SERVER";
$lang['go_conference_server'] = "CONFERENCIAS EN ESTE SERVIDOR"; //"CONFERENCES WITHIN THIS SERVER";
$lang['go_conference'] = "conferencia"; //"conference";



// [6] System Settings
$lang['go_version'] = "Versión"; //"Version";
$lang['go_db_schema_version'] = "Base de datos de esquema Versión"; //"DB Schema Version";
$lang['go_db_schema_update_date'] = "Base de datos de esquema de actualización Fecha"; //"DB Schema Update Date";
$lang['go_auto_user_add_value'] = "Auto usuario añadir valor"; //"Auto User-add Value";
$lang['go_install_date'] = "Fecha Instalar"; //"Install Date";
$lang['go_use_non_latin'] = "Use no Latino"; //"Use Non-Latin";
$lang['go_webroot_writable'] = "Webroot grabable"; //"Webroot Writable";
$lang['go_vicidial_agent_disable_display'] = "Vicidial Agente Desactivar Pantalla"; //"Vicidial Agent Disable Display";
$lang['go_allow_sipsak_msgs'] = "Permitir SIPSAK Mensajes"; //"Allow SIPSAK Messages";
$lang['go_admin_home_url'] = "Admin URL Inicio"; //"Admin Home URL";
$lang['go_admin_modify_refresh'] = "Administrador Modificar Actualizar"; //"Admin Modify Refresh";
$lang['go_admin_no_cache'] = "Administrador No-Cache"; //"Admin No-Cache";
$lang['go_enable_agent_transfer_logfile'] = "Activar agente de transferencia de archivos de registro"; //"Enable Agent Transfer Logfile";
$lang['go_enable_agent_disposition_logfile'] = "Activar agente Disposición de archivo de registro"; //"Enable Agent Disposition Logfile";
$lang['go_timeclock_end_of_day'] = "Reloj de tiempo Fin de Día"; //"Timeclock End Of Day";
$lang['go_default_local_gmt'] = "Por defecto GMT Local"; //"Default Local GMT";
$lang['go_timeclock_last_auto_logout'] = "Reloj de tiempo Última sesión automático"; //"Timeclock Last Auto Logout";
$lang['go_agent_screen_header_date_format'] = "Agente pantalla de cabecera Formato de fecha"; //"Agent Screen Header Date Format";
$lang['go_agent_screen_customer_date_format'] = "Agente de pantalla Fecha de clientes Formato"; //"Agent Screen Customer Date Format";
$lang['go_agent_screen_customer_phone_format'] = "Agente de la pantalla Formato de Atención Telefónica"; //"Agent Screen Customer Phone Format";
$lang['go_agent_api_active'] = "Agente API Activo"; //"Agent API Active";
$lang['go_agent_only_callback_campaign_lock'] = "Agente Sólo bloqueo Campaña de devolución de llamada"; //"Agent Only Callback Campaign Lock";
$lang['go_central_sound_control_active'] = "Centro de Control de Sonido Activo"; //"Central Sound Control Active";
$lang['go_sounds_web_server'] = "Suena servidor Web"; //"Sounds Web Server";
$lang['go_sounds_web_directory'] = "Suena Directorio Web"; //"Sounds Web Directory";
$lang['go_system_settings'] = "Configuración del sistema"; //"System Settings";
$lang['go_admin_web_directory'] = "Directorio Web de administración"; //"Admin Web Directory";
$lang['go_active_voicemail_server'] = "Servidor de correo de voz activa"; //"Active Voicemail Server";
$lang['go_auto_dial_limit'] = "Límite de marcación automática"; //"Auto Dial Limit";
$lang['go_outbound_auto_dial_active'] = "Outbound Auto-Dial Activo"; //"Outbound Auto-Dial Active";
$lang['go_max_fill_calls_per_second'] = "FILL máximo de órdenes por segundo"; //"Max FILL Calls per Second";
$lang['go_allow_custom_dialplan_entries'] = "Permitir entradas personalizadas Dialplan"; //"Allow Custom Dialplan Entries";
$lang['go_generate_cross_server_phone_extens'] = "Generar extensiones telefónicas entre servidores"; //"Generate Cross-Server Phone Extensions";
$lang['go_user_territories_active'] = "Territorios usuario activo"; //"User Territories Active";
$lang['go_enable_sound_webform'] = "Habilitar formulario Web Segunda"; //"Enable Second Webform";
$lang['go_enable_tts_integration'] = "Habilitar Integración TTS"; //"Enable TTS Integration";
$lang['go_enable_callcard'] = "Activar la tarjeta de llamada"; //"Enable CallCard";
$lang['go_enable_custom_list_fields'] = "Activar Campos Lista personalizada"; //"Enable Custom List Fields";
$lang['go_first_login_trigger'] = "Primera sesión de activación"; //"First Login Trigger";
$lang['go_default_phone_registration_pass'] = "Por defecto Registro contraseña del teléfono"; //"Default Phone Registration Password";
$lang['go_default_phone_login_pass'] = "Teléfono predeterminado sesión Contraseña"; //"Default Phone Login Password";
$lang['go_default_server_pass'] = "Servidor predeterminado Contraseña"; //"Default Server Password";
$lang['go_slave_database_server'] = "Esclavo del servidor de base de datos"; //"Slave Database Server";
$lang['go_reports_use_slave'] = "Informes de usar de base de datos del esclavo"; //"Reports to use Slave DB";
$lang['go_custom_dialplan_entry'] = "Entrada Dialplan personalizada"; //"Custom Dialplan Entry";
$lang['go_reload_dialplan_on_servers'] = "Actualizar Dialplan En los servidores"; //"Reload Dialplan On Servers";
$lang['go_l_title'] = "Etiqueta Título"; //"Label Title";
$lang['go_l_first_name'] = "Etiqueta Nombre"; //"Label First Name";
$lang['go_l_middle_name'] = "Etiqueta inicial del segundo"; //"Label Middle Initial";
$lang['go_l_last_name'] = "Etiqueta Apellido"; //"Label Last Name";
$lang['go_l_address1'] = "Etiqueta Dirección1"; //"Label Address1";
$lang['go_l_address2'] = "Etiqueta Dirección2"; //"Label Address2";
$lang['go_l_address3'] = "Etiqueta Dirección3"; //"Label Address3";
$lang['go_l_city'] = "Etiqueta de ciudad"; //"Label City";
$lang['go_l_state'] = "Estado Etiqueta"; //"Label State";
$lang['go_l_province'] = "Etiqueta Provincia"; //"Label Province";
$lang['go_l_postal_code'] = "Etiquetar código postal"; //"Label Postal Code";
$lang['go_l_vendor_lead_code'] = "Etiqueta de código de plomo Vendor"; //"Label Vendor Lead Code";
$lang['go_l_gender'] = "Etiqueta de Género"; //"Label Gender";
$lang['go_l_phone_number'] = "Etiqueta de número de teléfono"; //"Label Phone Number";
$lang['go_l_phone_code'] = "Etiqueta de código de teléfono"; //"Label Phone Code";
$lang['go_l_alt_phone'] = "Etiqueta Alt Teléfono"; //"Label Alt Phone<";
$lang['go_l_security_phrase'] = "Frase de seguridad etiqueta"; //"Label Security Phrase";
$lang['go_l_email'] = "Etiqueta de correo electrónico"; //"Label Email";
$lang['go_l_commens'] = "Comentarios de etiqueta"; //"Label Comments";
$lang['go_qc_features_active'] = "QC Características Activo"; //"QC Features Active";
$lang['go_qc_last_pull_time'] = "QC Última hora Tirar"; //"QC Last Pull Time";
$lang['go_default_codecs'] = "Códecs Predeterminados"; //"Default Codecs";
$lang['go_default_webphone'] = "Teléfono Web predeterminado"; //"Default Webphone";
$lang['go_default_external_server_ip'] = "Por defecto IP con servidor externo"; //"Default External Server IP";
$lang['go_webphone_url'] = "URL teléfono Web"; //"Webphone URL";
$lang['go_webphone_system_key'] = "Webphone clave del sistema"; //"Webphone System Key";

//[7] User Groups
$lang['go_user_groups'] = "Grupos de usuarios"; //"User Groups";
$lang['go_ug_entry_del'] = "ENTRADA GRUPO DE USUARIO"; //"USER GROUP ENTRY";
$lang['go_type'] = "tipo"; //"Type"; //
$lang['go_forced_timeclock'] = "Reloj de tiempo Forzada"; //"Forced Timeclock"; //
$lang['go_add_new_user_group'] = "Agregar nuevo grupo de usuarios"; //"Add New User Group";//

//Add New User Group
$lang['go_user_group_wizard'] = "Asistente de Grupo de Usuarios"; //"User Group Wizard"; //
$lang['add_new_user_group'] = "Agregar nuevo grupo de usuarios"; //"Add New User Group"; //
$lang['go_group_name'] = "Nombre del grupo"; //"Group Name"; //
$lang['go_group_template'] = "Plantilla Grupo"; //"Group Template"; //
$lang['go_user_groups_s'] = "Grupos de usuarios"; //"user groups";
$lang['go_ug_banner'] = "Grupos de usuarios"; //"User Groups";
$lang['go_to_1'] = "Ir a la primera página"; //"Go to First Page";
$lang['go_to_prev_p'] = "Ir a los Avances de Página"; //"Go to Previous Page";
$lang['go_to_page'] = "Ir a la página"; //"Go to Page";
$lang['go_to_next'] = "Ir a la página siguiente"; //"Go to Next Page";
$lang['go_to_last'] = "Ir a la última página"; //"Go to Last Page";
$lang['go_to_view_all'] = "Ir a ver todas las páginas"; //"Go to View All Pages";
$lang['go_to_back_pag'] = "Volver a Ver paginado"; //"Back to Paginated View";
$lang['go_group_level'] = "Nivel Grupo"; //"Group Level"; //
$lang['go_todays_status'] = "Estado de hoy"; //"Today's Status"; //
$lang['go_account_info'] = "Información de la cuenta"; //"Account Info"; //
$lang['go_agent_lead_status'] = "Estado del agente de plomo"; //"Agent Lead Status"; //
$lang['go_server_settings'] = "Configuración del servidor"; //"Server Settings"; //
$lang['go_go_analytics'] = "Ir Analytics"; //"Go Analytics"; //
$lang['go_system_service'] = "Servicio de sistema"; //"System Service"; //
$lang['go_cluster_status'] = "Estado del clúster"; //"Cluster Status"; //
$lang['go_create'] = "Crear"; //"Create"; //
$lang['go_read'] = "Leer"; //"Read"; //
$lang['go_update'] = "Actualización"; //"Update";
$lang['go_campaign'] = "Campaña"; //"Campaign"; //
$lang['go_list'] = "Lista"; //"List"; //
$lang['go_custom_fields'] = "Los campos personalizados"; //"Custom Fields"; //
$lang['go_load_leads'] = "Cables de carga"; //"Load Leads"; //
$lang['go_script'] = "Guiones"; //"Script"; //
$lang['go_reports_analytics'] = "Informes y análisis"; //"Reports & Analytics"; //
$lang['go_agent_time_detail'] = "Agente detalle tiempo"; //"Agent Time Detail"; //
$lang['go_agent_performance_detail'] = "Agente detalle el rendimiento"; //"Agent Performance Detail"; //
$lang['go_dial_status_summary'] = "Marcar resumen de estado"; //"Dial Status Summary"; //
$lang['go_sales_per_agent'] = "Ventas por agente"; //"Sales Per Agent"; //
$lang['go_sales_tracker'] = "Rastreador de ventas"; //"Sales Tracker"; //
$lang['go_inbound_call_report'] = "Informe de llamada entrante"; //"Inbound Call Report"; //
$lang['go_export_call_report'] = "Exportar informe de llamadas"; //"Export Call Report"; //
$lang['go_adv_script'] = "Avance de secuencias de comandos"; //"Advance Script"; //
$lang['go_recording'] = "Grabación"; //"Recording"; //
$lang['go_allowed_recording_view'] = "Mascotas grabación Ver"; //"Allowed Recording View"; //
$lang['go_support'] = "Apoyo"; //"Support"; //
$lang['go_allowed_support'] = "Soporte mascotas"; //"Allowed Support"; //
$lang['go_multi_tenant'] = "Multi-Tenant"; //
$lang['go_ug_navailable'] = "Grupo de Usuarios no disponible."; //"User Group Not Available.";
$lang['go_admin_logs'] = "Registros de administrador"; //"Admin Logs"; //
$lang['go_more'] = "más"; //"more";
$lang['go_call_times'] = "Llame Tiempos"; //"Call Times"; //
$lang['go_modify_ct'] = "MODIFICAR tiempo de atención"; //"MODIFY CALLTIME";
$lang['go_modify_sct'] = "Modificar el estado del TIEMPO DE LLAMADA"; //"MODIFY STATE CALLTIME";
$lang['go_del_sct'] = "BORRAR DEL ESTADO DE TIEMPO DE LLAMADA"; //"DELETE STATE CALLTIME";
$lang['go_del_con_gad'] = "¿Seguro que quieres eliminar tu GoAutoDial - cuenta JustGoVoIP? Esto eliminará tanto la entrada portadora y su JustGoVoIP"; //"Are you sure you want to delete your GoAutoDial - JustGoVoIP account? This will delete both the carrier entry and your JustGoVoIP";
$lang['go_del_con_irreversible'] = "¿Estás absolutamente QUIERE BORRAR SU CUENTA? Este proceso es irreversible"; //"ARE YOU ABSOLUTELY WANT TO DELETE YOUR ACCOUNT? THIS PROCESS IS IRREVERSIBLE";
$lang['go_carrier_del'] = "TRANSPORTISTA ENTRADA ELIMINADA"; //"CARRIER ENTRY DELETED";
$lang['go_carrier_banner'] = "Transportistas"; //"Carriers";
$lang['go_del_ct'] = "BORRAR LLAMADA TIEMPO"; //"DELETE CALLTIME";
$lang['go_call_time'] = "Duración de la llamada"; //"Call Time"; //
$lang['go_been_del'] = "ha sido borrado"; //"has been deleted";
$lang['go_phones_s'] = "Móviles"; //"Phones"; //
$lang['go_pls_sel_ext'] = "Por favor, seleccione una extensión."; //"Please select an Extension.";
$lang['go_del_con_sel_ext'] = "¿Seguro que quieres eliminar la extensión seleccionada"; //"Are you sure you want to delete the selected Extension";
$lang['go_phone_banner'] = "Móviles"; //"Phones"; //
$lang['go_phone_entry_del'] = "Portero"; //"PHONE ENTRY";
$lang['go_frm_list'] = "de la lista"; //"from the list";
$lang['go_phones'] = "móviles"; //"phones"; //
$lang['go_voicemails'] = "Los mensajes de voz"; //"Voicemails"; //
$lang['go_voicemails_s'] = "mensajes de voz"; //"voicemails"; //


//Modify (advance settings)
$lang['go_force_timeclock_login'] = "Fuerza reloj temporizador de inicio de sesión"; //"Force Timeclock Login"; //
$lang['go_shift_enforcement'] = "Aplicación Shift"; //"Shift Enforcement"; //
$lang['go_all_campaigns'] = "TODAS LAS CAMPAÑAS - Los usuarios pueden ver cualquier campaña"; //"ALL-CAMPAIGNS - USERS CAN VIEW ANY CAMPAIGN"; //
$lang['go_group_shifts']  = "Los cambios de grupo"; //"Group Shifts";
$lang['go_all'] = "ALL"; //"ALL";

//
$lang['go_modify_user_group'] = "Modificar Grupo de Usuarios"; //"Modify User Group"; //
$lang['no_record_found'] = "Ningún registro(s) encontrado"; //"No Record(s) Found";
$lang['go_modify_ug'] = "MODIFICAR GRUPO DE USUARIO"; //"MODIFY USER GROUP"; //
$lang['go_del_ug'] = "GRUPO ELIMINAR USUARIO"; //"DELETE USER GROUP"; //
$lang['go_allowed_campaign'] = "Campaña mascotas"; //"Allowed Campaign"; //
$lang['go_agent_status_viewable_groups'] = "Estado del agente que pueden verse en los Grupos"; //"Agent Status Viewable Groups"; //
$lang['go_all_groups'] = "Todos los Grupos"; //"All Groups"; //
$lang['all_ug_system'] = "- Todos los grupos de usuarios en el sistema"; //"- All user groups in the system"; //
$lang['go_campaign_agent'] = "Campaña AGENTES"; //"CAMPAIGN-AGENTS"; //
$lang['go_all_ulisc'] = "- Todos los usuarios registrados en la misma campaña"; //"- All users logged into the same campaign"; //
$lang['go_not_logged_agents'] = "No registrado-in-agents"; //"NOT-LOGGED-IN-AGENTS"; //
$lang['go_users_not_logged'] = "- Todos los usuarios del sistema, aún no se ha identificado en"; //" - All users in the system, even not logged-in"; //
$lang['go_agent_status_view_time'] = "Estado del agente Ver Tiempo"; //"Agent Status View Time"; //
$lang['go_agent_call_log_view'] = "Agente de Registro de llamadas Ver"; //"Agent Call Log View"; //
$lang['go_agent_allow_consultative_xfer'] = "Agente Permita Consultivo Reenviar"; //"Agent Allow Consultative Xfer";
$lang['go_agent_allow_dial_override_xfer'] = "Agente Deje de override Reenviar"; //"Agent Allow Dial Override Xfer";
$lang['go_agent_allow_voicemail_msg_xfer'] = "Agente Permitir correo de voz Mensaje Reenviar"; //"Agent Allow Voicemail Message Xfer";
$lang['go_agent_allow_blind_xfer'] = "Agente Permita Ciegos Reenviar"; //"Agent Allow Blind Xfer";
$lang['go_agent_allow_dial_with_customer'] = "Agente Permitir Dial Con Cliente Reenviar:"; //"Agent Allow Dial With Customer Xfer:";
$lang['go_agent_allow_park_customer_dial_xfer'] = "Agente Permita Parque Cliente Dial Reenviar"; //"Agent Allow Park Customer Dial Xfer";
$lang['go_agent_fullscreen'] = "Agente Pantalla Completa"; //"Agent Fullscreen"; //
$lang['go_allowed_reports'] = "Informes mascotas"; //"Allowed Reports"; //
$lang['go_allowed_user_groups'] = "Animales Grupos de usuarios"; //"Allowed User Groups"; //
$lang['go_all_groups'] = "ALL GROUPS"; //"ALL GROUPS"; //
$lang['go_all_ug_system'] = "- Todos los grupos de usuarios en el sistema"; //"- All user groups in the system"; //
$lang['go_allowed_call_times'] = "Animales de llams"; //"Allowed Call Times";
$lang['go_all_calltimes'] = "TODOS - TIEMPOS DE LLAMADA"; //"ALL-CALLTIMES";
$lang['go_all_call_times_system'] = "- Todos los tiempos de llamada en el sistema"; //" - All call times in the system";


//[8] Voicemails
$lang['go_vm_not_available'] = "Correo de voz no está disponible."; //"Voicemail Not Available.";
$lang['go_vm_banner'] = "Correo de voz"; //"Voicemails";
$lang['go_pls_select_vm'] = "Seleccione un buzón de voz."; //"Please select a Voicemail.";
$lang['go_voicemail_id'] = "Identificación del correo de voz";// "Identificación del correo de voz";/// "Voicemail ID"; //
$lang['go_name'] = "Nombre"; //"Name"; //
$lang['go_activate_selected'] = "Activar Seleccionada"; //"Activate Selected"; //
$lang['go_activate'] = "Activar"; //"Activate"; //
$lang['go_deactivate'] = "Desactivar"; //"Deactivate"; //
$lang['go_deactivate_selected'] = "Desactivar Seleccionado"; //"Deactivate Selected"; //

//Add New Voicemail
$lang['go_voicemail_wizard'] = "Asistente para correo de voz"; //"Voicemail Wizard"; //
$lang['go_add_new_voicemail'] = "Agregar nuevo correo de voz"; //"Add New Voicemail"; //
$lang['go_pass'] = "Contraseña"; //"Password"; //
$lang['go_active_no'] = "NO"; //"NO"; //
$lang['go_active_yes'] = "SÍ"; //"YES"; //
$lang['go_email'] = "Correo electrónico"; //"Email"; //
$lang['go_min_2_numbers'] = "Mínimo de 2 números"; //"Minimum of 2 numbers"; //
$lang['go_voicemails_boxes'] = "Cajas de mensajes de voz"; //"Voicemails Boxes"; //

//Modify voicemail
$lang['go_del_voicemail_after_email'] = "Eliminar correo de voz después de email"; //"Delete Voicemail After Email"; //
$lang['go_new_msg'] = "Nuevo Mensaje";//"New Messages"; //
$lang['go_old_msg'] = "Mensaje histórico"; //"Old Messages"; //
$lang['go_save_settings'] = "Guardar configuración"; //"Save Settings"; //
$lang['go_modify_voicemail_box'] = "Modificar el correo de voz"; //"Modify Voicemail box"; 
$lang['go_modify_voicemail'] = "MODIFICAR"; //"MODIFY";
$lang['go_del_con'] = "¿Seguro que quieres eliminar"; //"Are you sure you want to delete";

//Delete Voicemail
$lang['go_del_voicemail'] = "Eliminar correo de voz";
$lang['go_deld_voicemail'] = "Correo de voz eliminados:"; //"Deleted Voicemail(s):";
$lang['go_vm_entry_del'] = "ENTRADA DE VOZ"; //"VOICEMAIL ENTRY";
$lang['go_entry_3_char_search'] = "Introduzca por lo menos 3 caracteres para la búsqueda"; //"Please enter at least 3 characters to search";
$lang['go_del_voicemail_selected'] = "¿Seguro que quieres eliminar el correo de voz seleccionado"; //"Are you sure you want to delete the selected Voicemail";
$lang['go_del_voicemails'] = "ELIMINAR CORREO DE VOZ";
$lang['go_cancel'] = "Cancelar";
$lang['go_all_caps'] = "TODO";


$lang['go_usergroups_reports'] = "Todos los informes, NINGUNO, Tiempo real Informe principal, Resumen de campañas en tiempo real, Informe Inbound, Servicio Inbound Nivel Informe, Inbound Informe horario Resumen, Inbound Informe diario, Inbound DID Informe, Inbound IVR Informe, Outbound Informe Calling, Resumen de salida Intervalo Reportar , Informe IVR saliente, Fronter - Closer Informe, Listas Campaña Informe Los estados, Estado de la campaña Informe de lista, llamadas Exportar informe, las exportaciones genera Informe, Detalle de hora del agente, agente de detalles de estado, detalle Performance Agent, Detalle rendimiento del equipo, el Diario único agente, Timeclock usuario Informe del Grupo de Usuarios de estado de informe Timeclock, Detalle Timeclock usuario del informe, informe de ejecución del servidor, Administración Cambio de registro, Actualizar lista Estadísticas, Estadísticas de usuario, Hoja de tiempo de usuario, lista de descargas, Marcador informe de inventario, informes personalizados Links, CallCard Buscar, Máximo Estadísticas del Sistema, Máximo Estadísticas Detalle, Buscar Conductores de Registros"; //"ALL REPORTS, NONE, Real-Time Main Report, Real-Time Campaign Summary , Inbound Report, Inbound Service Level Report, Inbound Summary Hourly Report, Inbound Daily Report, Inbound DID Report, Inbound IVR Report, Outbound Calling Report, Outbound Summary Interval Report, Outbound IVR Report, Fronter - Closer Report, Lists Campaign Statuses Report, Campaign Status List Report, Export Calls Report , Export Leads Report , Agent Time Detail, Agent Status Detail, Agent Performance Detail, Team Performance Detail, Single Agent Daily , User Timeclock Report, User Group Timeclock Status Report, User Timeclock Detail Report , Server Performance Report, Administration Change Log, List Update Stats, User Stats, User Time Sheet, Download List, Dialer Inventory Report, Custom Reports Links, CallCard Search, Maximum System Stats, Maximum Stats Detail, Search Leads Logs";
$lang['go_off'] = "Apagado"; //"OFF";
$lang['go_pls_sel_one_more_camp'] = "Por favor seleccione una o más campañas de la lista."; //"Please select one or more campaign from the list.";
$lang['go_success_update'] = "Éxito: Actualización con éxito!"; //"Success: Update successful!";
$lang['go_defaultLanguage'] = "Lenguaje por defecto"; //"Default Language";
$lang['go_of'] = "de";
$lang['go_to'] = "al";
$lang['go_tenants_banner'] = "Múltiples-Inquilino";
$lang['go_telephony'] = "Telefonía"; //"Telephony";
$lang['go_admin_settings'] = "Configuraciones de admin"; //"Admin Settings";
$lang['go_lists_and_call_recordings'] = "Listas y llamadas Grabaciones"; //"Lists and Call Recordings";
$lang['go_call_reports'] = "Informes de llamadas"; //"Call Reports";
$lang['go_scripts'] = "Guiones"; //"Scripts"; //
$lang['go_reports'] = "Informes"; //"Reports";
$lang['go_analytics'] = "Analítica"; //"Analytics"; //
$lang['go_hello'] = "Hola"; //"Hello";
$lang['go_logout'] = "Cerrar sesión"; //"Logout";
$lang['go_edit_profile'] = "Edite su perfil"; //"Edit your profile";
$lang['go_powered_by_GAD'] = "Desarrollado por GoAutoDial";//"Powered by GoAutoDial";
$lang['go_go_back'] = "Volver"; //"Go Back";
$lang['go_active'] = "Activo"; //"Active";
$lang['go_show_on_screen'] = "Mostrar en pantalla"; //"Show on screen";
$lang['go_todays_status'] = "Estado de hoy"; //"Today's Status";
$lang['go_server_statistics'] = "Estadísticas del servidor"; //"Server Statistics";
$lang['go_configure'] = "Configurar"; //"Configure";
$lang['go_system_services'] = "Servicios del sistema"; //"System Services";
$lang['go_cluster_status'] = "Estado del Cluster"; //"Cluster Status";
$lang['go_justgovoip'] = "JustGOVoIP"; //"JustGOVoIP";
$lang['go_agents_status'] = "Estado del Agente"; //"Agent's Status";
$lang['go_plugins'] = "Plugins"; //"Plugins";
$lang['go_agents_phones'] = "Agentes y Móviles"; //"Agents & Phones";
$lang['go_analytics'] = "Ir Analytics"; //"GO Analytics";
$lang['go_gad_project_page'] = "GoAutoDial Página del proyecto"; //"GoAutoDial Project Page";
$lang['go_gad_news'] = "GOAutoDial noticias"; //"GOAutoDial News";
$lang['go_gad_community_forum'] = "GOAutoDial Comunidad y Foro"; //"GOAutoDial Community & Forum";
$lang['go_no_columns'] = "Número de columnas"; //"Number of Columns";
$lang['go_intro_help'] = "Introducción Ayuda"; //"Introduction Help";
$lang['go_notification'] = "Notificación"; //"Notification";
$lang['go_settings'] = "Ajustes"; //"Settings";




//Additional

$lang['go_description'] = "Descripción";
$lang['go_color'] = "Color";
$lang['go_active'] = "Activo";
$lang['go_web_form'] = "Formulario web";
$lang['go_next_agent_call'] = "Llamada de agente Siguiente";
$lang['go_oldest_call_finish'] = "oldest_call_finish";
$lang['go_overall_user_level'] = "overall_user_level";
$lang['go_inbound_group_rank'] = "inbound_group_rank";
$lang['go_campaign_rank'] = "campaign_rank";
$lang['go_fewest_calls'] = "fewest_calls";
$lang['go_fewest_calls_campaign'] = "fewest_calls_campaign";
$lang['go_longest_wait_time'] = "longest_wait_time";
$lang['go_ring_all'] = "ring_all";
$lang['go_queue_priority'] = "Cola de Prioridad";
$lang['go_fronter_display'] = "Pantalla Fronter";
$lang['go_script'] = "Guión";
$lang['go_on_hook_ring_time'] = "Colgado duración del timbre";
$lang['go_get_call_launch'] = "Obtener Lanzamiento Call";
$lang['go_webform'] = "FORMULARIO WEB";
$lang['go_transfer_conf_dtmf1'] = "Transfer-Conf DTMF 1";
$lang['go_transfer_conf_number1'] = "Transfer-Conf Numero 1";
$lang['go_transfer_conf_dtmf2'] = "Transfer-Conf DTMF 2";
$lang['go_transfer_conf_number2'] = "Transfer-Conf Numero 2";
$lang['go_transfer_conf_number3'] = "Transfer-Conf Numero 3";
$lang['go_transfer_conf_number4'] = "Transfer-Conf Numero 4";
$lang['go_transfer_conf_number5'] = "Transfer-Conf Numero 5";
$lang['go_tmer_action'] = "Temporizador Acción";
$lang['go_dial'] = "D1_MARCAR";
$lang['go_dial2'] = "D2_MARCAR";
$lang['go_dial3'] = "D3_MARCAR";
$lang['go_hangup'] = "COLGAR";
$lang['go_call_menu'] = "MENÚ LLAMADA";
$lang['go_exten'] = "AMPLIACIÓN";
$lang['go_ingroup'] = "EN_GRUPO";
$lang['go_timer_action_msg'] = "Temporizador de mensaje de acción";
$lang['go_timer_action_seconds'] = "Segundos Temporizador Acción";
$lang['go_timer_action_destination'] = "Temporizador Acción Destino";
$lang['go_drop_call_seconds'] = "Caiga segundos de llamada";
$lang['go_drop_action'] = "Acción gota";
$lang['go_drop_exten'] = "Gota Exten";
$lang['go_vm'] = "CORREO DE VOZ";
$lang['go_drop_transfer_group'] = "Gota Grupo de Transferencia";
$lang['go_call_time'] = "Duración de la llamada";
$lang['go_after_hours_action'] = "Después de horas de acción";
$lang['go_msg'] = "MENSAJE";
$lang['go_voicemail'] = "CORREO DE VOZ";
$lang['go_after_hours_msg_filename'] = "Después de horas de mensajes Nombre de archivo";
$lang['go_after_hours_exten'] = "Después de horas de Extensión";
$lang['go_after_hours_vm'] = "Después de horas de correo de voz";
$lang['go_after_hours_transfer_group'] = "Después Grupo Horas Transferencia";
$lang['go_no_agents_no_queueing'] = "No Agentes No Queueing";
$lang['go_no_paused'] = "NO_PAUSA";
$lang['go_no_agent_no_queue_action'] = "Ninguna acción agente No Queue";
$lang['go_welcome_me'] = "Mensaje de Bienvenida Nombre";
$lang['go_play_welcome_msg'] = "Juega Mensaje de Bienvenida";
$lang['go_never'] = "NUNCA";
$lang['go_if_wait_only'] = "IF_WAIT_ONLY";
$lang['go_yes_unless_nodelay'] = "YES_UNLESS_NODELAY";
$lang['go_moh_context'] = "Música en espera Contexto";
$lang['go_moh_chooser'] = "Moh Selector";
$lang['go_on_hold_prompt_filename'] = "On Hold Nombre Prompt";
$lang['go_on_hold_prompt_interval'] = "En espera de intervalo Prompt";
$lang['go_on_hold_prompt_no_block'] = "On Hold Prompt No Bloquear";
$lang['go_on_hold_prompt_seconds'] = "On Hold segundos Prompt";
$lang['go_play_place_line'] = "Juega Place en Línea";
$lang['go_play_estimated_hold_time'] = "Jugar Hold Time estimado";
$lang['go_calculate_estimated_hold_seconds'] = "Calcular segundos estimados Hold";
$lang['go_estimated_hold_time_min_filename'] = "Hold Tiempo estimado Nombre mínima";
$lang['go_estimated_hold_time_min_prompt_block'] = "Hold Time estimado mínimo Prompt No Bloquear";
$lang['go_estimated_hold_time_min_prompt_seconds'] = "Tiempo estimado Hold segundos Prompt mínimos";
$lang['go_wait_time_option'] = "Espere opción de tiempo";
$lang['go_press_stay'] = "PRESS_STAY";
$lang['go_press_vmail'] = "PRESS_VMAIL";
$lang['go_press_exten'] = "PRESS_EXTEN";
$lang['go_press_call_menu'] = "PRESS_CALLMENU";
$lang['go_press_cid_callback'] = "PRESS_CID_CALLBACK";
$lang['go_press_ingroup'] = "PRESS_INGROUP";
$lang['go_wait_time_second_option'] = "Tiempo de Espera de Segunda Opción";
$lang['go_press_cid_callback'] = "PRESS_CID_CALLBACK";
$lang['go_wait_time_third_option'] = "Tiempo de espera Tercera Opción";
$lang['go_wait_time_option_seconds'] = "Tiempo de espera segundos Opción";
$lang['go_wait_time_option_exten'] = "Tiempo de espera de Extensión Opción";
$lang['go_wait_time_option_call_menu'] = "Tiempo de espera del menú Opción de Compra";
$lang['go_wait_time_option_voicemail'] = "Tiempo de espera Opción de correo de voz";
$lang['go_wait_time_option_transfer_in_group'] = "Tiempo de espera de transferencia Opción A-Group";
$lang['go_wait_time_option_press_filename'] = "Tiempo de espera Opción Pulse Nombre del archivo";
$lang['go_wait_time_option_press_no_block'] = "Tiempo de espera Opción Pulse No Bloquear";
$lang['go_wait_time_option_press_filename_seconds'] = "Tiempo de espera oprima la opción segundos Nombre de archivo";
$lang['go_wait_time_option_after_press_filename'] = "Espere opción de tiempo Después de Prensa Nombre";
$lang['go_wait_time_option_callback_list_id'] = "Tiempo de espera Opción de devolución de llamada Lista ID";
$lang['go_wait_hold_option_priority'] = "Espere Hold Prioridad Opción";
$lang['go_both'] = "AMBOS";
$lang['go_wait'] = "ESPERE";
$lang['go_estimated_hold_time_option'] = "Opción Hold Time estimado";
$lang['go_hold_time_third_option'] = "Sostenga Tiempo Tercera Opción";
$lang['go_hold_time_option_seconds'] = "Mantener opciones segundo tiempo";
$lang['go_hold_time_option_minimum'] = "Mantenga Opción Tiempo mínimo";
$lang['go_hold_time_option_extension'] = "Sostenga Tiempo Extensión Opción";
$lang['go_hold_time_option_callmenu'] = "Tiempo de Espera menú Opción de Compra";
$lang['go_hold_time_option_voicemail'] = "Sostenga Tiempo Opción de correo de voz";
$lang['go_hold_time_option_transfer_in_group'] = "Sostenga Tiempo de transferencia Opción A-Group";
$lang['go_hold_time_option_press_filename'] = "Mantenga Opción hora Pulse Nombre del archivo";
$lang['go_hold_time_option_press_no_block'] = "Mantenga opción de tiempo Pulse No Bloquear";
$lang['go_hold_time_option_press_filename_seconds'] = "Hold Time Opción Pulse segundos Nombre de archivo";
$lang['go_hold_time_option_after_press_filename'] = "Mantenga opción de tiempo Después de Prensa Nombre";
$lang['go_hold_time_option_callback_list_id'] = "Sostenga Tiempo Opción de devolución de llamada Lista ID";
$lang['go_agent_alert_filename'] = "Alerta Agente Nombre";
$lang['go_agent_alert_delay'] = "agente de retraso en la alerta";
$lang['go_default_transfer_group'] = "Por defecto Transferencia Grupo";
$lang['go_agentdirect_single_agent_direct_queue'] = "AGENTDIRECT - Single agente directo cola";
$lang['go_default_group_alias'] = "Por defecto Grupo Alias";
$lang['go_hold_recall_transfer_in_group'] = "Hold Recall Transfer In-Group";
$lang['go_no_delay_call_route'] = "Sin retardo Ruta de llamada";
$lang['go_in_group_recording_override'] = "En-Grupo de anulación de grabación";
$lang['go_disabled'] = "go_ondemand";
$lang['go_allcalls'] = "TODAS LAS LLAMADAS";
$lang['go_allforce'] = "TODA LA FUERZA";
$lang['go_in_group_recording_filename'] = "En-Recording Group Nombre";
$lang['go_stats_percent_of_calls_answered_within_seconds'] = "Estadísticas Porcentaje de llamadas contestadas dentro de X segundos 1";
$lang['go_stats_percent_of_calls_answered_within_seconds'] = "Estadísticas Porcentaje de llamadas contestadas dentro de X segundos 1";
$lang['go_start_call_url'] = "Iniciar llamada URL";
$lang['go_dispo_call_url'] = "Dispo URL llamada";
$lang['go_add_lead_url'] = "Añadir URL Plomo";
$lang['go_extension_append_cid'] = "Extensión Append CID";
$lang['go_uniqueid_status_display'] = "Estado uniqueid Display";
$lang['go_enabled'] = "HABILITADO";
$lang['go_enabled_prefix'] = "ENABLED_PREFIX";
$lang['go_enabled_preserve'] = "ENABLED_PRESERVE";
$lang['go_uniqueid_status_prefix'] = "Prefijo Estado uniqueid";


############################### Login ################################
$lang['go_forgot_pass'] = "Has olvidado tu contraseña"; //"Forgot password";
$lang['go_mysql_connect_error'] = "MySQL conectar ERROR:"; //"MySQL connect ERROR:";
$lang['go_cloud_call_center'] = "Nube Call Center"; //"Cloud Call Center";
$lang['go_pls_enter_username'] = "Introduzca su nombre de usuario."; //"Please enter your username.";
$lang['go_pls_enter_pass'] = "Por favor, que introduzca la contraseña."; //"Please enter you password.";
$lang['go_close'] = "CERRAR"; //"CLOSE";
$lang['go_agent_login'] = "Conexión del agente"; //"AGENT LOGIN";
$lang['go_account_number'] = "Número de cuenta / Web ingreso:"; //"Account Number / Web Login:";
$lang['go_send_email'] = "Enviar al correo electrónico"; //"Send to email";
$lang['go_the_account_does_not_exist'] = "No existe el número de cuenta."; //"The Account number does not exist.";
$lang['go_from'] = "desde"; //"From";
$lang['go_justgocloud_admin_pass_ret'] = "JustGoCloud Admin Password retrival."; //"JustGoCloud Admin Password retrival.";
$lang['go_pls_keep_this_email'] = "Por favor, mantenga este mensaje para sus registros. La información de su cuenta es el siguiente"; //"Please keep this email for your records. Your account information is as follows";
$lang['go_account_number'] = "Número De Cuenta"; //"Account Number";
$lang['go_justgocloud_admin_url'] = "JustGoCloud administración URL"; //"JustGoCloud Admin URL";
$lang['go_web_admin_username'] = "Web Nombre de usuario Admin"; //"Web Admin Username";
$lang['go_admin_pass'] = "Web contraseña de administrador"; //"Web Admin Password";
$lang['go_pass_sent'] = "Contraseñas enviado a"; //"Passwords sent to";
$lang['go_your_username'] = "Su nombre de usuario"; //"Your username";
$lang['go_username'] = "Nombre de usuario"; //"Username";
$lang['go_your_pass'] = "Su contraseña"; //"Your password";
$lang['go_password'] = "Contraseña"; //"Your password";
$lang['go_login'] = "INICIAR SESIÓN"; //"LOGIN";
$lang['go_create_account'] = "Crear una cuenta"; //"Create an account";
$lang['go_proceeding_agree'] = "Al continuar, acepto la"; //"By proceeding, I agree to the ";
$lang['go_terms_service'] = "Términos de servicio </a> y "; //"Terms of Service </a> and";
$lang['go_privacy_notice'] = "Confidencialidad </a> de $VARCLOUDCOMPANY"; //"Privacy Notice</a> of Goautodial Ltd.";
$lang['go_redirecting'] = "Redireccionando..."; //"Redirecting...";
$lang['go_conf_file_not_found'] = "ERROR: El archivo 'goautodial.conf' no encontrado."; //"ERROR: 'goautodial.conf' file not found.";
$lang['go_ast_file_not_found'] = "ERROR: El archivo 'astguiclient.conf' no encontrado."; //"ERROR: 'astguiclient.conf' file not found.";
$lang['go_empowering_generation'] = "Capacitar a los Contact Centers de Próxima Generación"; //"Empowering the Next Generation Contact Centers";
$lang['go_gad_ce'] = "GoAutoDial CE 3.0 viene con ninguna garantía o garantías de ningún tipo, ya sea escrita o implícita. La distribución se libera en forma"; //"GoAutoDial CE 3.0 comes with no guarantees or warranties of any sorts, either written or implied. The Distribution is released as";
$lang['go_ce1'] = "Los paquetes individuales en la distribución vienen con sus propias licencias."; //"Individual packages in the distribution come with their own licences.";
$lang['go_and'] = "y"; //"and";
$lang['go_registered_trademark'] = "son marcas registradas de GoAutoDial Inc. registrada"; //"are registered trademarks of GoAutoDial Inc.";
$lang['go_gad_alr'] = "GoAutoDial Inc. 2010-2014 Todos los derechos reservados."; //"GoAutoDial Inc. 2010-2014 All Rights Reserved.";


################################ IVR ##################################

$lang['go_menu_id_navailable'] = "Identificación del menú no está disponible.";
$lang['go_menu_id_should_nempty'] = "Identificación del menú no debe estar vacío.";
$lang['go_min_4_digits'] = "Mínimo de 4 dígitos.";
$lang['go_call_menu_wizard'] = "Asistente de menú de llamada";
$lang['go_create_new_call_menu'] = "Crear Nuevo Menú de llamada";
$lang['go_menu_id'] = "Identificación del menú";
$lang['go_menu_name'] = "Nombre del menú";
$lang['go_menu_greeting'] = "Saludo menú";
$lang['go_menu_timeout'] = "Menú de tiempo de espera";
$lang['go_menu_timeout_greeting'] = "Menú de saludo de tiempo de espera";
$lang['go_menu_repeat'] = "Menú de repetición";
$lang['go_menu_time_check'] = "Menú de control horario";
$lang['go_call_time'] = "Duración de la llamada";
$lang['go_track_calls'] = "Pista llama en";
$lang['go_real_time_report'] = "Informe en tiempo real";
$lang['go_tracking_group'] = "Grupo de seguimiento";
$lang['go_user_group'] = "Grupo de seguimiento";
$lang['go_default_call_menu_entry'] = "Llamada por defecto entrada de menú";
$lang['go_description'] = "Descripción";
$lang['go_audio_file'] = "Archivo de audio";
$lang['go_add_new_call_menu_options'] = "Añadir nuevas opciones de menú llamada";
$lang['go_route'] = "Ruta";
$lang['go_descriptions'] = "Descriptions";
$lang['go_prompt'] = "Rápido";
$lang['go_timeout'] = "Se acabó el tiempo";
$lang['go_modify_ivr'] = "MODIFICAR IVR";
$lang['go_delete_ivr'] = "BORRAR IVR";
$lang['go_ivr_menus'] = "Respuesta de Voz Interactiva (IVR) Menús";
$lang['go_option_sel_already_use'] = "La opción que ha seleccionado ya está en uso.";
$lang['go_modify_callmenu'] = "MODIFICAR CALLMENU";
$lang['go_menu_prompt'] = "Aviso de menú";
$lang['go_menu_timeout_prompt'] = "Símbolo del tiempo de espera de menú";
$lang['go_menu_invalid_prompt'] = "Aviso de menú no válida";
$lang['go_track_calls_real_time_report'] = "Llamadas seguir en tiempo real Informe";
$lang['go_tracking_group'] = "Grupo de Seguimiento";
$lang['go_custom_dialplan_entry'] = "Entrada Dialplan personalizada";
$lang['go_call_menu_options_'] = "Opciones del menú de llamada";
$lang['go_in_groups'] = "En grupos";
$lang['go_del_call_menu'] = "¿Seguro que quieres borrar este menú llamada / IVR";
$lang['go_permission_del_call_menu'] = "Error: Usted no tiene permiso para eliminar la llamada Menú / IVR.";

$lang['go_ERROR'] = "ERROR";
$lang['go_Thecampaignyouwanttodeletestillhaslistidsthathaveleadsloaded'] = "La campaña(s) que desea borrar todavía tiene lista de identificadores que tienen cables cargados";
$lang['go_PleasetransfertheexistingliststoanothercampaignordeleteitusingourListpage'] = "Por favor transfiera la lista ( s ) existente a otra campaña o eliminarla con nuestra página Lista";
$lang['go_UserTooltip'] = "Usuarios - permiten la creación de agentes y usuarios administradores.";
$lang['go_ScriptTooltip2'] = 'Una secuencia de comandos permite administrador para permitir una ventana popup en la página web del Agente durante una llamada en vivo cuando se hace clic en el botón "guión". Cada elemento de la "Guión de texto" es una sintaxis que consigue pegado en el cuadro de texto cada vez que el "insertar" se hace clic en el botón y permite que el sistema para llamar la información específica sobre los archivos cargados de plomo o la información del sistema, como los nombres de agentes y lo mostrará en una ventana cuando el agente presiona el botón de secuencia de comandos en la interfaz de usuario del agente (UI).';
$lang['go_AnsweringMachineDetectionTooltip'] = "<b> Contestar Detección máquina </ b> \ n
Off - Todas las llamadas contestadas automáticamente serán enviados a un agente. \ n
On - Sistema intentará distinguir si la llamada es recogida por un contestador automático. Tasa de detección es en el mejor de 70%";
$lang['go_ActiveStatusDialTooltip'] = "<b> Marcar Estado </ b> - Especifica las disposiciones sobre el archivo principal activo (s) en la campaña que el sistema marcará automáticamente. No serán marcados Cualquier disposiciones no incluidas en el estado de línea.";
$lang['go_Customer3WayHangupActionTooltip'] = "<b> Cliente 3-Way Colgar Acción </ b> - Si se pone a dispo, esto se llevará a la página agente a la pantalla de la disposición cuando el sistema detecta que el cliente tiene hungup en la llamada de 3 vías.";
$lang['go_ActionColumnTooltip'] = "<b> Columna Acción </ b> - proporciona opciones de administración adicionales, tales como editar, ampliar información, borrar y y descargar la lista.";
$lang['goUsers_tooltip_level'] = "Nivel - define el permiso concedido a un usuario. Los ajustes actuales son; Nivel 1 - 6 - Nivel Agent. Sólo se puede acceder a la conexión del agente. No se puede modificar la configuración de la cuenta. Privilegio Limited. Nivel 7-8 - Nivel de administrador. Se puede acceder tanto de conexión del agente y administrador salpicadero. Puede realizar cambios en Configuración de la cuenta.";
$lang['goUsers_agentandphoneTooltip'] =  "<b> Agente ID </ b> y <b> Teléfono entrada </ b> se utilizan para registrar su softphone y acceder a su página web agente. Ellos no se pueden cambiar.";
$lang['go_widgetDateTooltip'] = "<b> Rango de fechas </ b> - determina la longitud de tiempo de datos que se generen";
$lang['goUsers_forceLogoutTooltip'] = '<b> Fuerza Salir </ b> - terminará la sesión del navegador del agente. Esto es útil cuando el agente está recibiendo el error "logout impropia".';
$lang['go_inboundthistimeTooltip'] = "<b> llamadas salientes en esta ocasión </ b> - muestra toda la información pertinente sobre las llamadas salientes en el intervalo de fechas seleccionado.";

$lang['go_group_not_added'] = "GRUPO NO AÑADIDO - Por favor, volver atrás y mirar a los datos introducidos";
$lang['go_did_not_added'] = "No AÑADIDO - ya existía.";
$lang['go_error_unknown_service'] = "Error: Desconocido servicio";
$lang['go_error_multi_gad_carrier'] = "Error: Las entradas múltiples portadoras GoAutoDial no están permitidos";
$lang['go_error_registered_user'] = "Error: Es usted un usuario registrado?";
$lang['go_error_pls_contact_support'] = "Error: Algo salió mal por favor póngase en contacto con soporte";
$lang['go_pls_use_freshdesk'] = "Cuenta de administrador? Por favor, use nuestro freshdesk";
$lang['go_new_ticket_submited'] = "Nueva solicitud presentada";
$lang['go_added_new_note'] = "Añadido nuevo billete éxito";
$lang['go_error_no_script_process'] = "Error: No script para procesar!";
$lang['go_error_empty_scriptid'] = "Error: scriptid vacío";
$lang['go_success_new_lime_survey'] = "Éxito: Nueva encuesta cal creado";
$lang['go_error_saving_data_support'] = "Error al guardar datos de contacto de su apoyo";
$lang['go_error_no_data_process'] = "Error: no hay datos para procesar";
$lang['go_error_passing_not_obj_var'] = "Error: no pasa una variable de objeto";
$lang['go_error_passing_empty_data'] = "Error: Pasar datos vacíos en contacto con su apoyo";
$lang['go_dnc_numbers'] = "Números DNC";
?>
<?php

//START OF REPORTS

######################go_reports.php###################
//Pagetitle
$lang['go_statistical_rep'] 	      = "Informe Estadístico"; //"Statistical Report"; 
$lang['go_agent_stats_rep'] 	      = "Estadísticas agente de informes"; //"Agent Stats Report";
$lang['go_dial_statuses_summary_rep'] = "Marque Los estados Informe resumido"; //"Dial Statuses Summary Report";
$lang['go_sales_per_agent_rep']       = "Ventas por informe Agente"; //"Sales Per Agent Report";
$lang['go_oi_sales_tracker'] 	      = "Saliente / Entrante Ventas Rastreador Reportar"; //"Outbound/Inbound Sales Tracker Report";
$lang['go_inbound_rep'] 	      = "Informe Inbound"; //"Inbound Report";
$lang['go_export_call_rep'] 	      = "Exportar informe de llamadas"; //"Export Call Report";
$lang['go_dashboard'] 		      = "Salpicadero"; //"Dashboard";
$lang['gocdr'] = "CDR";
//bannertitle
$lang['go_reports_analytics'] 	      = "Informes y Analytics"; //"Reports & Analytics";
$lang['go_reports_analytics_tooltip'] = "Informes y Analytics - le dará prácticamente todos los datos que necesita sobre su cuenta. Los informes se pueden descargar y en formato de hoja de cálculo. Hay una amplia variedad de informa puedes elegir con cada uno informes personalizables de adaptar a sus necesidades. También se mostrará en pantalla un gráfico comparando diferentes datos en relación con los demás. Cada tipo de informe será discutido en detalle en las páginas siguientes."; /*"Reports and Analytics . will give you practically every data you need regarding your <br> account. Reports are downloadable and in spreadsheet format. There is a wide variety of
<br>reports you can choose from with each reports customizable to tailor to your needs.<br> It will also display an onscreen graph comparing different data in relation to each other. <br>Each type of report will be discussed in detail in the succeeding pages.";*/
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
$lang['go_lists'] 		= "Listas:"; //"Lists:";
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
$lang[''] = "";



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
$lang['go_all_caps'] = "TODO"; //"ALL";
$lang['go_all_user_groups_caps'] = "TODOS LOS GRUPOS DE USUARIOS"; //"ALL USER GROUPS";
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
$lang['go_moh_listing'] = "Música en espera (MOH) Listados"; //"Music On Hold (MOH) Listings";
$lang['go_modify_moh'] = "MODIFICAR LA MÚSICA EN ESPERA"; //"MODIFY MUSIC ON HOLD";
$lang['go_moh_name'] = "Música en espera Nombre"; //"Music on Hold Name";
$lang['go_moh'] = "Música en espera"; //"Music on Hold";
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
$lang['go_all_user_groups'] = "Todos los grupos de usuarios"; //"All User Groups";
$lang['go_moh_item'] = "música elemento de retención"; //"music on hold item";
$lang['go_advance_script'] = "Advance Script";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";


#############################Interactive Voice response (IVR) Menu #################################################
/*


$lang['go_menu_id_navailable'] = "menu id not available.";
$lang['go_menu_id_should_nempty'] = "menu id should not be empty.";
$lang['go_menu_id_navailable'] = "menu id not available.";
$lang['go_menu_id_should_nempty'] = "menu id should not be empty.";
$lang['go_min_4_digits'] = "minimum of 4 digits.";
$lang['go_call_menu_wizard'] = "call menu wizard";
$lang['go_create_new_call_menu'] = "create new call menu";
$lang['go_menu_id'] = "menu id";
$lang['go_menu_name'] = "menu name";
$lang['go_menu_greeting'] = "menu greeting";
$lang['go_menu_timeout'] = "menu timeout";
$lang['go_menu_timeout_greeting'] = "menu timeout greeting";
$lang['go_menu_invalid_greeting'] = "menu invalid greeting";
$lang['go_menu_repeat'] = "menu repeat";
$lang['go_menu_time_check'] = "menu time check";
$lang['go_call_time'] = "call time";
$lang['go_track_calls'] = "track calls in";
$lang['go_real_time_report'] = "real-time report";
$lang['go_tracking_group'] = "tracking group";
$lang['go_user_group'] = "user group";
$lang['go_default_call_menu_entry'] = "default call menu entry<";
$lang['go_description'] = "description:";
$lang['go_audio_file'] = "audio file";
$lang['go_add_new_call_menu_options'] = "add new call menu options";
$lang['go_route'] = "route";
$lang['go_descriptions'] = "descriptions";
$lang['go_prompt'] = "prompt";
$lang['go_timeout'] = "timeout";
$lang['go_modify_ivr'] = "modify ivr";
$lang['go_delete_ivr'] = "delete ivr";
$lang['go_ivr_menus'] = "interactive voice response (ivr) menus";
$lang['go_option_sel_already_use'] = "The option you selected is already in use.";
$lang['go_modify_callmenu'] = "MODIFY CALLMENU";
$lang['go_menu_prompt'] = "Menu Prompt";
$lang['go_menu_timeout_prompt'] = "Menu Timeout Prompt";
$lang['go_menu_invalid_prompt'] = "Menu Invalid Prompt";
$lang['go_track_calls_real_time_report'] = "Track Calls in Real-Time Report";
$lang['go_tracking_group'] = "Tracking Group";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_call_menu_options_'] = "Call Menu Options";

*/

$lang['go_menu_id_navailable'] = "Identificación del menú no está disponible.";
$lang['go_menu_id_should_nempty'] = "Identificación del menú no debe estar vacío.";
$lang['go_min_4_digits'] = "Mínimo de 4 dígitos.";
$lang['go_call_menu_wizard'] = "asistente de menú de llamada";
$lang['go_create_new_call_menu'] = "crear nuevo menú de llamada";
$lang['go_menu_id'] = "Identificación del menú";
$lang['go_menu_name'] = "nombre del menú";
$lang['go_menu_greeting'] = "saludo menú";
$lang['go_menu_timeout'] = "menú de tiempo de espera";
$lang['go_menu_timeout_greeting'] = "menú de saludo de tiempo de espera";
$lang['go_menu_repeat'] = "menú de repetición";
$lang['go_menu_time_check'] = "menú de control horario";
$lang['go_call_time'] = "duración de la llamada";
$lang['go_track_calls'] = "pista llama en";
$lang['go_real_time_report'] = "informe en tiempo real";
$lang['go_tracking_group'] = "grupo de seguimiento";
$lang['go_user_group'] = "grupo de seguimiento";
$lang['go_default_call_menu_entry'] = "llamada por defecto entrada de menú";
$lang['go_description'] = "descripción";
$lang['go_audio_file'] = "archivo de audio";
$lang['go_add_new_call_menu_options'] = "añadir nuevas opciones de menú llamada";
$lang['go_route'] = "ruta";
$lang['go_descriptions'] = "descriptions";
$lang['go_prompt'] = "rápido";
$lang['go_timeout'] = "se acabó el tiempo";
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
$lang['go_in_groups'] = "en grupos";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";






?>

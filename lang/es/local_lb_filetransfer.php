<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Plugin strings are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    string
 * @copyright   2021 eCreators PTY LTD
 * @author      2021 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Transferencia de Archivos de Learnbook';
$string['pluginname_description'] = 'Plugin de transferencia de archivos de learnbook para automatizar la carga de usuarios usando un archivo CSV de un repositorio remoto.';

//task
$string['filetransfertask_upload'] = 'Carga de usuarios';
$string['filetransfertask_report'] = 'Informe de salida';
$string['filetransfertask_auth_error'] = 'No es posible autenticar usando usuario y contraseña para la conexión con id: {$a->id}.';
$string['filetransfertask_key_error'] = 'No es posible autenticar usando la llave rsa para la conexión con id: {$a->id}.';
$string['filetransfertask_configreport_error'] = 'No fue posible encontra el reporte configurable con id: {$a->id}.';
$string['filetransfertask_reportsend'] = 'Reporte con id: {$a->id} ha sido enviado al directorio remoto.';
$string['filetransfertask_reporemailed'] = 'Reporte con id: {$a->id} ha sido enviado al correo: {$a->email}.';
$string['filetransfertask_filearchive'] = 'Limpieza y archivado han sido realizados para el reporte con id: {$a->id}.';
$string['filetransfertask_fileread_error'] = 'No se pudo leer el archivo para la conexión con id: {$a->id}.';
$string['filetransfertask_nofile_error'] = 'No se pudo encontrar el archivo para la conexión con id: {$a->id}.';
$string['filetransfertask_userupload'] = 'La carga de usuarios terminó satisfactoriamente para la instancia de carga de usuarios con id: {$a->id}.';
$string['filetransfertask_userupload_error'] = 'Carga de usuarios para la instancia con id: {$a->id} no fue satisfactoria, por favor revise el archivo csv.';
$string['filetransfertask_connection_error'] = 'Carga de usuarios para la instancia con id: {$a->id} no fue satisfactoria, por favor revise sus ajustes de conexión.';
$string['filetransfertask_userfilearchive'] = 'Limpieza y archivado han sido realizados para la instancia de carga de usuarios con id: {$a->id}.';

//event
$string['filetransferstarted'] = 'El proceso de transferencia de archivos ha comenzado.';
$string['filetransferevent'] = 'Procesando archivos';
$string['filetransfersuccess'] = 'El proceso de transferencia de archivos y carga de usuarios se completó satisfactoriamente.';
$string['filetransfererror'] = 'Error, no se pudo obtener el archivo del directorio remoto';
$string['filetransfererrorcsv'] = 'Carga de usuarios no exitosa, revise el archivo csv cargado.';

//general table
$string['go-back'] = 'Regresar';
$string['createnew'] = 'Crear Muevo';
$string['id'] = 'Id';
$string['name'] = 'Nombre';
$string['status'] = 'Estado';
$string['timecreated'] = 'Hora de creación';
$string['timemodified'] = 'Hora de modificación';
$string['actions'] = 'Acciones';
$string['active'] = 'Activo';
$string['inactive'] = 'Inactivo';
$string['activate'] = 'Activar';
$string['deactivate'] = 'Desactivar';
$string['connectioninfo'] = 'Información de la conexión';

//index-page
$string['config_connections'] = 'Configurar conexiones';
$string['config_useruploads'] = 'Configurar carga de usuarios';
$string['config_outgoingreports'] = 'Configurar reportes de salida';

//connections page
$string['delete_connection'] = 'Eliminar Conexión';
$string['delete_connection_confirmation'] = 'Está seguro que desea eliminar esta conexión: {$a->name}.';
$string['connection_deleted'] = 'Conexión: {$a->name} eliminada.';
$string['connection_delete_failed'] = 'No se pudo eliminar la conexión: {$a->name}, por favor elimine la información asociada antes de eliminar esta conexión.';
$string['connection_active'] = 'Conexión: {$a->name} está activa.';
$string['connection_active_error'] = 'No se puede activar la conexión: {$a->name}.';
$string['connection_deactive'] = 'Conexión: {$a->name} está desactivada.';
$string['connection_deactive_error'] = 'No se puede desactivar la conexión: {$a->name}, por favor elimine la información asociada antes de hacer cambios.';
$string['connection_saved'] = 'Conexión guardada exitosamente';
$string['connection_save_error'] = 'Error al guardar la conexión';

//connections form
$string['name'] = 'Nombre de la conexión';
$string['connectiontype'] = 'Tipo de conexión';
$string['connection_sftp'] = 'SFTP';
$string['connection_ftp'] = 'FTP';
$string['hostname'] = 'Nombre de Host';
$string['portnumber'] = 'Número de Puerto';
$string['username'] = 'Usuario';
$string['password'] = 'Contraseña';
$string['usepublickey'] = 'Usa llave pública';
$string['privatekey'] = 'Llave privada';
$string['yes'] = 'Si';
$string['no'] = 'No';
$string['number_only'] = 'Solamente carácteres numéricos';
$string['maximum_character_255'] = 'Máximo 255 carácteres';
$string['maximum_character_1024'] = 'Máximo 1024 carácteres';

//useruploads form
$string['pathtofile'] = 'Ruta al archivo';
$string['filename'] = 'Nombre del archivo';
$string['twoweeks'] = '2 Semanas';
$string['fourweeks'] = '4 Semanas';
$string['archivefile'] = 'Archivar información in moodledata';
$string['archiveperiod'] = 'Archivar periodo';
$string['connectionid'] = 'Seleccionar conexión';
$string['uutype'] = 'Tipo de Carga';
$string['uuoptype_addinc'] = 'Agregar todos, unir numero al usuario si es necesario';
$string['uuoptype_addnew'] = 'Agregar nuevos solamente, saltar usuarios existentes';
$string['uuoptype_addupdate'] = 'Agregar nuevos and actualizar usuarios existentes';
$string['uuoptype_update'] = 'Actualizar usuarios existentes solamente';
$string['uupasswordnew'] = 'Nueva contraseña de usuario';
$string['infilefield'] = 'Campo requerido en el archivo';
$string['createpasswordifneeded'] = 'Crear contraseña si es necesario y enviarla via email';
$string['uuupdatetype'] = 'Detalles de usuario existente';
$string['nochanges'] = 'No hay cambios';
$string['uuupdatefromfile'] = 'Sobreescribir con el archivo';
$string['uuupdateall'] = 'Sobreescribir con el archivo y configuración por defecto';
$string['uuupdatemissing'] = 'Llenar la información faltante con el archivo y configuración por defecto';
$string['uupasswordold'] = 'Contraseña de usuario existente';
$string['nochanges'] = 'No hay cambios';
$string['update'] = 'Actualizar';
$string['allowrename'] = 'Permitir cambios de nombres';
$string['allowdeletes'] = 'Permitir eliminaciones';
$string['allowsuspend'] = 'Permitir suspender y activar cuentas';
$string['noemailduplicate'] = 'Sin duplicados de cuentas de email';
$string['standardusername'] = 'Estandarizar los nombres de usuarios';
$string['yes'] = 'Si';
$string['no'] = 'No';
$string['delete_processed'] = 'Eliminar los archivos remotos después de procesarlos';
$string['move_remotefile'] = 'Mover los archivos remotos después de procesarlos';
$string['move_remotefile_directory'] = 'Ruta para el directorio después de procesar los archivos (en ubicación remota)';
$string['move_failed_files'] = 'Mover los archivos no exitosos a un directorio diferente';
$string['move_failed_files_directory'] = 'Directorio para archivos no exitosos';
$string['getlatestfile'] = 'Obtener el último archivo modificado del directorio remoto';
$string['emaillog'] = 'Reporte de log de email';
$string['decryptfile'] = 'Desencriptar archivo entrante';
$string['decryptionkey'] = 'Llave de Desencriptación';
$string['decryptiontype'] = 'Tipo de Desencriptación';
$string['decryptiontype_aes'] = 'RSA';

//useruploads page
$string['delete_userupload'] = 'Eliminar instancia de carga de usuarios';
$string['delete_userupload_confirmation'] = 'Está seguro que desea eliminar esta instancia de carga de usuarios: {$a->name}.';
$string['userupload_deleted'] = 'Instancia de carga de usuarios: {$a->name} eliminada.';
$string['userupload_delete_failed'] = 'No se pudo eliminar esta instancia de carga de usuarios: {$a->name}.';
$string['userupload_active'] = 'Instancia de carga de usuarios: {$a->name} está activa.';
$string['userupload_active_error'] = 'No se pudo activar esta instancia de carga de usuarios: {$a->name}.';
$string['userupload_deactive'] = 'Instancia de carga de usuarios: {$a->name} está desactivada.';
$string['userupload_deactive_error'] = 'No se puede desactivar esta instancia de carga de usuarios: {$a->name}.';
$string['userupload_saved'] = 'Información de carga de usuarios guardada exitosamente.';
$string['userupload_save_error'] = 'Error guardando la información de carga de usuarios';

//outgoing report form
$string['email'] = 'direcciones de correo electrónico (separadas por coma, sin espacio)';
$string['configurablereportid'] = 'Seleccionar reporte';
$string['emailpreference'] = 'Preferencias de email (enviar emails con)';
$string['emailpreference_report'] = 'Archivo de reporte';
$string['emailpreference_log'] = 'Log de informaciín';
$string['emailpreference_both'] = 'Ambos reporte & log';
$string['outgoingreportpreference'] = 'Preferencias de reporte (enviar el archivo a)';
$string['outgoingreportpreference_remote'] = 'Directorio remoto';
$string['outgoingreportpreference_email'] = 'Email';
$string['outgoingreportpreference_both'] = 'Ambos directorio remoto & email';
$string['encryptfile'] = 'Cifrar archivo saliente';
$string['encryptionkey'] = 'Llave de cifrado';
$string['encryptiontype'] = 'Tipo de cifrado';
$string['privatekey'] = 'Llave privada';

//outgoing reports page
$string['delete_outgoingreport'] = 'Eliminar instancia de reporte de salida';
$string['delete_outgoingreport_confirmation'] = 'Está seguro que desea eliminar esta instancia de reporte de salida: {$a->name}.';
$string['outgoingreport_deleted'] = 'Instancia de reporte de salida: {$a->name} Eliminado.';
$string['outgoingreport_delete_failed'] = 'No se pudo eliminar esta instancia de reporte de salida: {$a->name}.';
$string['outgoingreport_active'] = 'Instancia de reporte de salida: {$a->name} está activa.';
$string['outgoingreport_active_error'] = 'No se puede activar esta instancia de reporte de salida: {$a->name}.';
$string['outgoingreport_deactive'] = 'Instancia de reporte de salida: {$a->name} está desactivada.';
$string['outgoingreport_deactive_error'] = 'No se puede desactivar esta instancia de reporte de salida: {$a->name}.';
$string['outgoingreport_saved'] = 'Información de reporte de salida guardada exitosamente.';
$string['outgoingreport_save_error'] = 'Error al guardar la información de reporte de salida.';

//outgoing report email
$string['outgoingreport_email_subject'] = 'Reporte de transferencia de archivos de Learnbook';
$string['outgoingreport_email_body'] = 'Hola, el reporte de transferencia de archivos de Learnbook está listo para ti. Por favor revisa el archivo CSV adjunto';
$string['outgoingreport_logemail_subject'] = 'Log de transferencia de archivos de Learnbook';

//userupload log email
$string['userupload_log_report'] = 'Reporte de carga de usuarios de Learnbook';

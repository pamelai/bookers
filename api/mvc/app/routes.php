<?php

use pifp\Core\Route;


Route::add('POST', '/login', 'UsuarioController@login');

Route::add('POST', '/logout', 'UsuarioController@logout');

Route::add('POST', '/registrarse', 'UsuarioController@registro');

Route::add('PUT', '/perfil/editar', 'UsuarioController@editar');

Route::add('DELETE', '/perfil/eliminar', 'UsuarioController@eliminar');

Route::add('GET', '/novedadesListado/{usr}', 'NovedadesController@listado');

Route::add('GET', '/novedadesListado/{usr}/{id}', 'NovedadesController@listado');

Route::add('POST', '/novedades/publicar', 'NovedadesController@publicar');

Route::add('POST', '/novedades/comentarios', 'ComentariosController@publicar');

Route::add('DELETE', '/novedades/eliminar', 'NovedadesController@eliminarNov');

Route::add('POST', '/novedades/favorito', 'FavoritoController@agregarFav');

Route::add('GET', '/novedades/favoritos/{id}', 'FavoritoController@listado');

Route::add('DELETE', '/novedades/favorito', 'FavoritoController@eliminarFav');

Route::add('POST', '/notificacion/crear', 'NotificacionesController@crear');

Route::add('GET', '/notificacion/{id}', 'NotificacionesController@listado');

Route::add('PUT', '/notificacion/lectura', 'NotificacionesController@marcarLeida');

Route::add('PUT', '/notificacion/vaciar', 'NotificacionesController@eliminar');

Route::add('POST', '/novedades/compartir', 'NovedadesController@compartir');

Route::add('GET', '/usuario/intereses/{id}', 'InteresesController@listado');

Route::add('POST', '/usuario/intereses/crear', 'InteresesController@crear');

Route::add('DELETE', '/usuario/intereses/eliminar', 'InteresesController@eliminar');

Route::add('POST', '/novedades/tag/crear', 'TagsController@crear');

Route::add('GET', '/novedades/tag/{id}', 'TagsController@busqueda');

// Rutas de eventos
Route::add('GET', '/eventos/{id}', 'EventosController@listado');

Route::add('GET', '/eventos/perfil/{id}', 'EventosController@eventosPerfil');

Route::add('POST', '/eventos/asistir', 'EventosController@asistir');

Route::add('GET', '/eventos/proximos', 'EventosController@eventosProximos');


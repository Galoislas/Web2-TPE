# Entrega-TPE3
-Galo Islas
-Bruno Bianchi

API de Comentarios

Esta API permite gestionar comentarios mediante operaciones CRUD.

Crear comentario (POST)

URL:
http://localhost/Entrega_3_TPE/api/comentarios

Body (JSON):
{
"nombre": "marcos",
"comentario": "muy buena pelicula",
"puntuacion": 7
}

Obtener todos los comentarios (GET)

URL:
http://localhost/Entrega_3_TPE/api/comentarios

Respuesta:
[
{
"id": 12,
"nombre": "marcos",
"comentario": "muy buena pelicula",
"puntuacion": 7
}
]

Ordenamiento (GET con query params)

Por defecto los resultados se ordenan por nombre en forma ascendente.

Ejemplo:
http://localhost/Entrega_3_TPE/api/comentarios?sort=DESC&table=puntuacion

Parámetros:
sort: ASC o DESC
table: nombre, comentario o puntuacion

Obtener comentario por id (GET)

URL:
http://localhost/Entrega_3_TPE/api/comentarios/:id

Ejemplo:
http://localhost/Entrega_3_TPE/api/comentarios/12

Filtrar por puntuación mínima (GET)

URL:
http://localhost/Entrega_3_TPE/api/comentarios/filter?puntuacionMinima=5

Actualizar comentario (PUT)

URL:
http://localhost/Entrega_3_TPE/api/comentarios/:id

Body (JSON):
{
"nombre": "marcos",
"comentario": "actualizado",
"puntuacion": 8
}

Eliminar comentario (DELETE)

URL:
http://localhost/Entrega_3_TPE/api/comentarios/:id

musicBox
========

La empresa “Music Box” lo ha contratado a usted para que desarrolle una aplicación, que les permita poner 
en marcha un servicio web de archivos de audio. 
El conjunto de software que se debe desarrollar deberá cumplir con los
siguientes requerimientos:
• Interfaz Web:
Se debe construir una aplicación web, que servirá de interfaz de usuario,
donde la persona podrá seleccionar el archivo que desea “partir” en varias
partes.
Cuando el usuario seleccione un archivo para ser partido, es sistema debe:
▪ Subir el archivo y guardarlo en alguna ubicación en el servidor
▪ Encolar un mensaje en algún servidor de colas (Sugerido “Rabbitmq”)
▪ Los mensajes en la cola deben enviarse en formato json y deben
presentar la siguiente estructura: {id: 1, file: '/tmp/file1.mp3', parts: 6,
time_per_chunk: “2 minutes”}
▪ El id de cada mensaje encolado debe corresponder al consecutivo del
id de una tabla de base de datos.
▪ El usuario tendrá dos opciones en la interfaz web.
• Partir la canción en N partes iguales.
• Partir la canción partes de N minutos cada una.
• Módulo Queue worker:
Se requiere desarrollar una aplicación de línea de comandos que se ejecute
en “background”. Esta aplicación será la encargada de separar el archivo de
audio en varias partes. (El sitio web NO hace ningún trabajo relacionado con
archivos de audio).
▪ La aplicación de consola debe monitorear el servidor de colas y
cuando entren mensajes, debe hacer la separación lo más rápido
posible y colocar los nuevos archivos en algún lugar en el servidor
para que puedan ser descargados por el usuario.
▪ Debe actualizar la tabla de base de datos con los urls en donde
estarán disponibles los archivos recién separados.
▪ El usuario deberá ir viendo la lista de todos los archivos que hayan
sido generados en tiempo real, es decir, sin refrescar la página.
▪ Se deberá desarrollar esta applicación conforme a la documentación
presente en el sitio web del framework laravel.
(http://laravel.com/docs/4.2/queues)

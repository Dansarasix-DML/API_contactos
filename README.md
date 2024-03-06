Añade el siguiente Virtual Host para probar la API:

```
<VirtualHost contactos.es:80>
    DocumentRoot "C:/xampp/htdocs/DWES/Ud8/contactos/public/index.php"
    ServerName contactos.es
    <Directory C:/xampp/htdocs/DWES/Ud8/contactos/public/>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Y añade esta línea en tus hosts: ``127.0.0.1 contactos.es``

Para probar la autentificación cambiar en el ``bootstrap.php`` el valor de la variable ``KK`` a ``true``.

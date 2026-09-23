// I no hi ha alguna manera d'obrir el modul que està dins el container amb VSCode? Si.
// És millor doncs pots obrir amb VSCode la carpeta /var/www/francescroy.com i així
// el plugin de PHP de VSCode funciona millor...
// Inclús clona'l dins del container... per treballar a un únic lloc...

docker cp hello_world.zip ubuntu_fra:/
mv /hello_world.zip /var/www/francescroy.com/modules/custom/
rm -rf hello_world
unzip hello_world.zip
php /var/www/francescroy.com/vendor/bin/drush.php un hello_world
php /var/www/francescroy.com/vendor/bin/drush.php en hello_world
php /var/www/francescroy.com/vendor/bin/drush.php cache-rebuild


/* A remirar...

Com instal.lo el mòdul? Simplement movent-lo a custom folder? Si. Però abans d'habilitar-lo, s'ha de fer composer install a ell! 

a no ser que ho fes com tocaria i seria posant-lo en el composer.json del drupal...! -> ?

-------------------------------------------------------

Això es d'un modul antic...

root@11011a6c3d2c:/var/www/hola.com# touch example.log
root@11011a6c3d2c:/var/www/hola.com# chmod 777 example.log 

/var/www/hola.com/vendor/bin/drush cr


*/


// TODO:

Posar adreça completa - falta poble, codi postal, pais - Done
També a la segona pantalla! - Done
Mirar de crear be l'activitat segons el format CJ... - Done
Podria ser el hash del contacte en comptes del dni? -> {contact.hash}, que al correu és Marques del contacte - done
Multi-idioma - done
Falta link a la segona template... - done
Nom pais en el idioma... - done
Que sigui un mòdul usable per altres campanyes...
Estil professional repositori
A qui se li envia????




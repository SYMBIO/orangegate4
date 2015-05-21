OrangeGate4 CMS
===============

OrangeGate4 CMS is built upon the great Sonata Admin project.

Instalation
===========

1. composer create-project symbio/orangegate4

1. php app/console doctrine:schema:update --force

1. php app/console fos:user:create
   admin

1. php app/console fos:user:promote<br>
   admin<br>
   ROLE_SUPER_ADMIN

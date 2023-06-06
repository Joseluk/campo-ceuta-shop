<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version00000000000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Datos iniciales';
    }

    public function up(Schema $schema): void
    {
        $this->addSQL("insert into \"categoria\" (\"id\", \"nombre\") values (1, 'Cortacéspedes');");
        $this->addSQL("insert into \"categoria\" (\"id\", \"nombre\") values (2, 'Tractores Cortacésped');");
        $this->addSQL("insert into \"categoria\" (\"id\", \"nombre\") values (3, 'Rotovatores');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Proveedor de cortadoras, podadoras, aspiradoras', 0, 'BullMatch');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Cortacéspedes', 2, 'Blackstone');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Cortacéspedes', 3, 'GeoTech');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Tractores', 4, 'Alpina');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Tractores', 5, 'Snapper');");
        $this->addSQL("insert into \"proveedor\" (\"descripcion\", \"id\", \"nombre\") values ('Rotovatores', 6, 'AgriEuro');");
        $this->addSQL("insert into \"usuario\" (\"contrasena\", \"domicilio\", \"email\", \"id\", \"nombre\", \"roles\") values ('$2y$13$Ih3QLmpe2O6ze.PDMTA2MuONrdykHP33g/wttq6hnZGF5C5sFMHqS', 'Calle Test', 'admin@admin.com', 2, 'Admin01', '{\"ROLE_ADMIN\"}');");
        $this->addSQL("insert into \"usuario\" (\"contrasena\", \"domicilio\", \"email\", \"id\", \"nombre\", \"roles\") values ('$2y$13$N13j0KTPcjdTiYhg70RFUOZwH69bVgSIPo9AfJhjJhcJNUo9t7/9C', 'Calle Mendoza, nº 9, 2ºB', 'jefe@campoceuta.es', 5, 'Jefe01', '{\"ROLE_JEFE\"}');");
        $this->addSQL("insert into \"usuario\" (\"contrasena\", \"domicilio\", \"email\", \"id\", \"nombre\", \"roles\") values ('$2y$13$Ih3QLmpe2O6ze.PDMTA2MuONrdykHP33g/wttq6hnZGF5C5sFMHqS', 'Calle Test2', 'admin2@admin.com', 3, 'JefeAdmin', '{\"ROLE_ADMIN\",\"ROLE_JEFE\"}');");
        $this->addSQL("insert into \"usuario\" (\"contrasena\", \"domicilio\", \"email\", \"id\", \"nombre\", \"roles\") values ('$2y$13$rSZ2XaND7vNio65jIWNcwOUQAru3Ok.7PMJb2x8NTJbVqBW.VCOgu', 'Calle Usuario 02', 'usuario02@usuario.com', 7, 'Usuario02', '{\"ROLE_USER\"}');");
        $this->addSQL("insert into \"usuario\" (\"contrasena\", \"domicilio\", \"email\", \"id\", \"nombre\", \"roles\") values ('$2y$13$9UfxMktjGuUUk2lvqRL1rODGSsCTCBWDxeF0OFsbz4hemIdsf8YPS', 'Calle Usuario', 'usuario@usuario.com', 6, 'Usuario01', '{\"ROLE_USER\"}');");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Bienvenido a CampoCeuta Usuario01, no olvides revisar nuestra sección de Rotovatores.', 2, '2023-06-06 03:02:10', 7, true, 6);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Encantado de conocerte Admin 2', 5, '2023-06-05 09:38:19', 3, true, 3);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Espero que todo esté yendo bien. Me gustaría programar una reunión de seguimiento para discutir el progreso del e-commerce de tractores. Por favor, hágame saber cuál es el mejor momento para usted esta semana. Estoy ansioso por escuchar sobre los últimos desarrollos y abordar cualquier desafío que estemos enfrentando. ¡Gracias!', 5, '2023-06-05 09:43:43', 5, true, 2);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Solo quería recordarle que el informe trimestral del e-commerce de tractores debe presentarse antes del final de esta semana. Asegúrese de incluir todos los datos relevantes sobre ventas, inventario, marketing y cualquier otro aspecto importante del negocio. Esto nos ayudará a evaluar nuestro rendimiento y tomar decisiones estratégicas. Si necesita alguna asistencia, no dude en ponerse en contacto conmigo. ¡Gracias!', 5, '2023-06-05 09:42:22', 4, true, 2);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Felicitaciones por los resultados del trimestre', 5, '2023-06-05 09:34:38', 2, true, 2);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Un proceso de compra fluido y eficiente es crucial para garantizar una experiencia positiva para nuestros clientes.', 5, '2023-06-05 20:50:38', 6, true, 2);");
        $this->addSQL("insert into \"mensaje\" (\"contenido\", \"emisor\", \"fecha_envio\", \"id\", \"leido\", \"receptor\") values ('Buenos días Jefe', 2, '2023-06-05 09:29:00', 1, true, 5);");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('ACHILLE 51 MY22 Motor 224 cc', 1, 1, 0, 'cortacesped-autopropulsado-de-gasolina-bullmach-achille-51-my22-4-en-1-motor-224-cc-cortacesped-bullmach-achille-51-my22-28342-41-1657704689-img-62ce90f1babfc-647e4c2d0bc8f687515786.jpg', 'BullMatch', 'Cortacésped autopropulsado de gasolina 4 en 1', '2023-06-05 20:57:17');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('BMSWGE L225, mono-rueda delantera', 2, 1, 3, 'cortacsped-autopropulsado-geotech-pro-s58-3-bmswge-l225-mono-rueda-delantera-cortacsped-geotech-pro-s58-3-bmswge-l225-30151-0-1625572628-img-60e445146e2d5-647e4c8a11430673726767.jpg', 'GeoTech', 'Cortacésped autopropulsado Pro S58-3', '2023-06-05 20:58:50');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Deluxe con motor Blackstone Y196V', 4, 1, 2, 'cortacsped-autopropulsado-de-gasolina-blackstone-sp480-deluxe-con-motor-blackstone-y196v-agrieuro-16048-2-647e4fa6ddbc3937617323.jpg', 'Blackstone', 'Cortacésped autopropulsado de gasolina  SP480', '2023-06-05 21:12:06');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('CERBERO 53 H - Motor Honda GCVx200', 3, 1, 0, 'cortacsped-autopropulsado-de-gasolina-bullmach-cerbero-53-h-motor-honda-gcvx200-cortacsped-bullmach-cerbero-53-h-27850-0-1617185668-img-60644b84b6b8b-transformed-647e8ad9d60a8858813876.jpeg', 'BullMach', 'Cortacésped autopropulsado de gasolina BullMach', '2023-06-06 01:24:41');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('51cm con motor Briggs&Stratton 625', 5, 1, 4, 'cortacsped-autopropulsado-de-gasolina-alpina-al7-51-bs-51cm-con-motor-briggs-stratton-625-cortacsped-alpina-al7-51-bs-30153-6-1625618933-img-60e4eab179142-647e50288a5ef601020208.jpg', 'Alpina', 'Cortacésped autopropulsado de gasolina AL7-51 BS', '2023-06-06 01:25:32');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('53 cm con motor Briggs&Stratton 675EXi', 6, 1, 5, 'cortacsped-autopropulsado-de-gasolina-snapper-sp-60-53-cm-con-motor-briggs-stratton-675exi-cortacsped-snapper-sp-60-30155-4-1625619061-img-60e4eade579cc-647e5076e29f7448406809.jpg', 'Snapper', 'Cortacésped autopropulsado de gasolina SP 60', '2023-06-06 01:26:29');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Motore Honda GX 160', 7, 3, 6, 'motocultor-a-gasolina-agrieuro-tx70-s-con-motore-honda-gx-160-motocultor-agrieuro-tx70-s-27264-7-1623807979-img-60d90f6be3c5c-647e67a88b768279504680.jpg', 'AgriEuro', 'Motocultor a gasolina TX70 S', '2023-06-06 01:33:50');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Motoazada a gasolina TX70 -Ruedas desmontables', 8, 3, 6, 'motocultor-a-gasolina-agrieuro-tx50-s-motoazada-a-gasolina-tx70-ruedas-desmontables-27110-2-1623808346-img-60d9129b206b1-647e67f43d150913074678.jpg', 'AgriEuro', 'Motoazada a gasolina TX70', '2023-06-06 01:34:10');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Motor Honda GX 270', 9, 3, 6, 'motocultor-a-gasolina-agrieuro-tx80-s-con-motore-honda-gx-270-motocultor-agrieuro-tx80-s-27268-6-1623808203-img-60d90fba29f166-647e67d8d7f74960127681.jpg', 'AgriEuro', 'Motocultor a gasolina TX80 S', '2023-06-06 01:34:41');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Potencia motor 6 HP - Trituradora reversible', 10, 2, 6, 'biotrituradora-a-gasolina-agrieuro-bio-350-trituradora-de-ramas-agrieuro-bio-350-29201-4-647e6939d6f3e575355882.jpg', 'AgriEuro', 'Biotrituradora a gasolina BIO 350', '2023-06-06 01:37:15');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Potencia motor 15 HP', 11, 2, 7, 'biotrituradora-a-gasolina-ceccato-eco-minor-5-15-hp-rotor-con-3-cuchillas-trituradora-de-ramas-ceccato-eco-minor-5-29648-8-1657778286-img-62d1a31a6435031016809.jpg', 'Ceccato', 'Biotrituradora a gasolina Eco Minor 5', '2023-06-06 01:40:53');");
        $this->addSQL("insert into \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") values ('Potencia motor 14 HP - Rotor con 2 cuchillas', 12, 2, 8, 'biotrituradora-a-gasolina-ceccato-tiger-6-rotor-con-2-cuchillas-trituradora-de-ramas-ceccato-tiger-6-30161-3-1657778583-img-62d1a59f64a1121916851.jpg', 'Ceccato', 'Biotrituradora a gasolina Tiger 6', '2023-06-06 01:41:25');");

        $this->addSQL("CREATE TABLE IF NOT EXISTS \"telefono\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"numero\" VARCHAR(255) NOT NULL, \"usuario_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_93FED4AA76ED395\" FOREIGN KEY (\"usuario_id\") REFERENCES \"usuario\" (\"id\"));");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"categoria\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"nombre\" VARCHAR(255) NOT NULL, \"imagen\" VARCHAR(255) NOT NULL, \"descripcion\" VARCHAR(255) NOT NULL);");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"proveedor\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"nombre\" VARCHAR(255) NOT NULL, \"direccion\" VARCHAR(255) NOT NULL, \"telefono\" VARCHAR(255) NOT NULL, \"email\" VARCHAR(255) NOT NULL);");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"pedido\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"fecha\" DATETIME NOT NULL, \"estado\" VARCHAR(255) NOT NULL, \"total\" DECIMAL(10, 2) NOT NULL, \"usuario_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_68C303D0A76ED395\" FOREIGN KEY (\"usuario_id\") REFERENCES \"usuario\" (\"id\"));");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"detalle_pedido\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"cantidad\" INTEGER NOT NULL, \"subtotal\" DECIMAL(10, 2) NOT NULL, \"pedido_id\" INTEGER DEFAULT NULL, \"producto_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_D1A5E8CCA76ED395\" FOREIGN KEY (\"pedido_id\") REFERENCES \"pedido\" (\"id\"), CONSTRAINT \"FK_D1A5E8CC451827D9\" FOREIGN KEY (\"producto_id\") REFERENCES \"producto\" (\"id\"));");
        $this->addSQL("CREATE INDEX IF NOT EXISTS \"IDX_D1A5E8CCA76ED395\" ON \"detalle_pedido\" (\"pedido_id\");");
        $this->addSQL("CREATE INDEX IF NOT EXISTS \"IDX_D1A5E8CC451827D9\" ON \"detalle_pedido\" (\"producto_id\");");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"usuario\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"nombre\" VARCHAR(255) NOT NULL, \"email\" VARCHAR(255) NOT NULL, \"password\" VARCHAR(255) NOT NULL);");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"direcciones_envio\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"direccion\" VARCHAR(255) NOT NULL, \"ciudad\" VARCHAR(255) NOT NULL, \"codigo_postal\" VARCHAR(255) NOT NULL, \"usuario_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_4E4FC9FAA76ED395\" FOREIGN KEY (\"usuario_id\") REFERENCES \"usuario\" (\"id\"));");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"carrito\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"usuario_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_A77C94F2A76ED395\" FOREIGN KEY (\"usuario_id\") REFERENCES \"usuario\" (\"id\"));");
        $this->addSQL("CREATE TABLE IF NOT EXISTS \"item_carrito\" (\"id\" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, \"cantidad\" INTEGER NOT NULL, \"subtotal\" DECIMAL(10, 2) NOT NULL, \"carrito_id\" INTEGER DEFAULT NULL, \"producto_id\" INTEGER DEFAULT NULL, CONSTRAINT \"FK_18997CC1A76ED395\" FOREIGN KEY (\"carrito_id\") REFERENCES \"carrito\" (\"id\"), CONSTRAINT \"FK_18997CC1451827D9\" FOREIGN KEY (\"producto_id\") REFERENCES \"producto\" (\"id\"));");
        $this->addSQL("CREATE INDEX IF NOT EXISTS \"IDX_18997CC1A76ED395\" ON \"item_carrito\" (\"carrito_id\");");
        $this->addSQL("CREATE INDEX IF NOT EXISTS \"IDX_18997CC1451827D9\" ON \"item_carrito\" (\"producto_id\");");
        $this->addSQL("INSERT INTO \"usuario\" (\"id\", \"nombre\", \"email\", \"password\") VALUES (1, 'John Doe', 'john.doe@example.com', 'password');");
        $this->addSQL("INSERT INTO \"usuario\" (\"id\", \"nombre\", \"email\", \"password\") VALUES (2, 'Jane Smith', 'jane.smith@example.com', 'password');");

        $this->addSQL("INSERT INTO \"categoria\" (\"id\", \"nombre\", \"imagen\", \"descripcion\") VALUES (1, 'Cortacésped', 'cortacesped.jpg', 'Categoría de cortacéspedes y cortabordes.');");
        $this->addSQL("INSERT INTO \"categoria\" (\"id\", \"nombre\", \"imagen\", \"descripcion\") VALUES (2, 'Biotrituradoras', 'biotrituradoras.jpg', 'Categoría de biotrituradoras y astilladoras.');");
        $this->addSQL("INSERT INTO \"categoria\" (\"id\", \"nombre\", \"imagen\", \"descripcion\") VALUES (3, 'Motocultores', 'motocultores.jpg', 'Categoría de motocultores y motoazadas.');");

        $this->addSQL("INSERT INTO \"proveedor\" (\"id\", \"nombre\", \"direccion\", \"telefono\", \"email\") VALUES (1, 'Alpina', '123 Main St, City', '+123456789', 'info@alpina.com');");
        $this->addSQL("INSERT INTO \"proveedor\" (\"id\", \"nombre\", \"direccion\", \"telefono\", \"email\") VALUES (2, 'Snapper', '456 Elm St, City', '+987654321', 'info@snapper.com');");
        $this->addSQL("INSERT INTO \"proveedor\" (\"id\", \"nombre\", \"direccion\", \"telefono\", \"email\") VALUES (3, 'AgriEuro', '789 Oak St, City', '+555555555', 'info@agrieuro.com');");
        $this->addSQL("INSERT INTO \"proveedor\" (\"id\", \"nombre\", \"direccion\", \"telefono\", \"email\") VALUES (4, 'Ceccato', '789 Maple St, City', '+111111111', 'info@ceccato.com');");

        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('41cm con motor Briggs&Stratton 300E', 1, 1, 1, 'cortacsped-elctrico-alpina-bl-380-e-41cm-con-motor-briggs-stratton-300e-cortacsped-alpina-bl-380-e-30151-5-1625618734-img-60e4ea78b3d9c-647e4fc512f269349904708.jpg', 'Alpina', 'Cortacésped eléctrico BL 380 E', '2023-06-06 01:23:10');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('46cm con motor Briggs&Stratton 450E', 2, 1, 1, 'cortacsped-autopropulsado-de-gasolina-alpina-al5-46-bs-46cm-con-motor-briggs-stratton-450e-cortacsped-alpina-al5-46-bs-30152-3-1625618862-img-60e4e9cda3d8a-647e4ff9727d5658779879.jpg', 'Alpina', 'Cortacésped autopropulsado de gasolina AL5-46 BS', '2023-06-06 01:24:07');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('51cm con motor Briggs&Stratton 625E', 3, 1, 2, 'cortacsped-elctrico-snapper-xd-41cm-51cm-con-motor-briggs-stratton-625e-cortacsped-snapper-xd-30153-1-1657793804-img-62d1b5fc75a8c601658786.jpg', 'Snapper', 'Cortacésped eléctrico XD 41cm/51cm con motor Briggs&Stratton 625E', '2023-06-06 01:25:24');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('56cm con motor Briggs&Stratton 675EXi', 4, 1, 2, 'cortacsped-autopropulsado-snapper-ninja-56cm-con-motor-briggs-stratton-675exi-cortacsped-snapper-ninja-30154-9-1657794039-img-62d1b67c0f5d8101660056.jpg', 'Snapper', 'Cortacésped autopropulsado Snapper Ninja', '2023-06-06 01:26:20');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 6,5 HP - Fresas desmontables', 5, 3, 3, 'motocultor-a-gasolina-agrieuro-mt-750-6-5-hp-con-fresas-desmontables-motocultor-agrieuro-mt-750-30150-7-647e671e3505f286057855.jpg', 'AgriEuro', 'Motocultor a gasolina MT 750', '2023-06-06 01:33:27');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 8 HP - Fresas desmontables', 6, 3, 3, 'motocultor-a-gasolina-agrieuro-tx80-s-8-hp-con-fresas-desmontables-motocultor-agrieuro-tx80-s-30149-3-647e67be2a15d563275233.jpg', 'AgriEuro', 'Motocultor a gasolina TX80 S', '2023-06-06 01:34:41');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 6 HP - Trituradora reversible', 10, 2, 3, 'biotrituradora-a-gasolina-agrieuro-bio-350-trituradora-de-ramas-agrieuro-bio-350-29201-4-647e6939d6f3e575355882.jpg', 'AgriEuro', 'Biotrituradora a gasolina BIO 350', '2023-06-06 01:42:01');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 9 HP - Trituradora reversible', 11, 2, 3, 'biotrituradora-a-gasolina-agrieuro-bio-910-trituradora-de-ramas-agrieuro-bio-910-29202-0-647e68e9c6e47286047003.jpg', 'AgriEuro', 'Biotrituradora a gasolina BIO 910', '2023-06-06 01:43:01');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 4,2 kW - Fresas ajustables', 12, 3, 4, 'motocultor-a-gasolina-ceccato-mr-60-4-2-kw-con-fresas-ajustables-motocultor-ceccato-mr-60-30155-0-647e676e2f03b576459780.jpg', 'Ceccato', 'Motocultor a gasolina MR 60', '2023-06-06 01:35:23');");
        $this->addSQL("INSERT INTO \"producto\" (\"especificacion\", \"id\", \"id_categoria\", \"id_proveedor\", \"imagen\", \"marca\", \"nombre\", \"updated_at\") VALUES ('Potencia motor 6,5 HP - Fresas ajustables', 13, 3, 4, 'motocultor-a-gasolina-ceccato-mr-70-6-5-hp-con-fresas-ajustables-motocultor-ceccato-mr-70-30156-7-647e6800e080a284926407.jpg', 'Ceccato', 'Motocultor a gasolina MR 70', '2023-06-06 01:36:18');");

        $this->addSQL("INSERT INTO \"pedido\" (\"id\", \"estado\", \"total\", \"usuario_id\") VALUES (1, 'entregado', 189.99, 1);");
        $this->addSQL("INSERT INTO \"pedido\" (\"id\", \"estado\", \"total\", \"usuario_id\") VALUES (2, 'pendiente', 249.99, 1);");
        $this->addSQL("INSERT INTO \"pedido\" (\"id\", \"estado\", \"total\", \"usuario_id\") VALUES (3, 'entregado', 499.99, 2);");
        $this->addSQL("INSERT INTO \"pedido\" (\"id\", \"estado\", \"total\", \"usuario_id\") VALUES (4, 'pendiente', 299.99, 2);");

        $this->addSQL("INSERT INTO \"detalle_pedido\" (\"id\", \"cantidad\", \"subtotal\", \"pedido_id\", \"producto_id\") VALUES (1, 1, 189.99, 1, 1);");
        $this->addSQL("INSERT INTO \"detalle_pedido\" (\"id\", \"cantidad\", \"subtotal\", \"pedido_id\", \"producto_id\") VALUES (2, 1, 249.99, 2, 2);");
        $this->addSQL("INSERT INTO \"detalle_pedido\" (\"id\", \"cantidad\", \"subtotal\", \"pedido_id\", \"producto_id\") VALUES (3, 2, 999.98, 3, 3);");
        $this->addSQL("INSERT INTO \"detalle_pedido\" (\"id\", \"cantidad\", \"subtotal\", \"pedido_id\", \"producto_id\") VALUES (4, 1, 299.99, 4, 4);");

        $this->addSQL("ALTER SEQUENCE categoria_id_seq RESTART WITH 4;");
        $this->addSQL("ALTER SEQUENCE proveedor_id_seq RESTART WITH 5;");
        $this->addSQL("ALTER SEQUENCE producto_id_seq RESTART WITH 14;");
        $this->addSQL("ALTER SEQUENCE pedido_id_seq RESTART WITH 5;");
        $this->addSQL("ALTER SEQUENCE detalle_pedido_id_seq RESTART WITH 5;");

        $this->addSQL("SELECT pg_catalog.setval(pg_get_serial_sequence('categoria', 'id'), 4, false);");
        $this->addSQL("SELECT pg_catalog.setval(pg_get_serial_sequence('proveedor', 'id'), 5, false);");
        $this->addSQL("SELECT pg_catalog.setval(pg_get_serial_sequence('producto', 'id'), 14, false);");
        $this->addSQL("SELECT pg_catalog.setval(pg_get_serial_sequence('pedido', 'id'), 5, false);");
        $this->addSQL("SELECT pg_catalog.setval(pg_get_serial_sequence('detalle_pedido', 'id'), 5, false);");

        $this->endStep();

    }

    public function down(Schema $schema): void
    {

    }
}

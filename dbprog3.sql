DROP DATABASE IF EXISTS dbprog3;
CREATE DATABASE IF NOT EXISTS dbprog3;
USE dbprog3;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios
(
    id       int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre   varchar(45)  DEFAULT NULL,
    apellido varchar(45)  DEFAULT NULL,
    usuario  varchar(45)     NOT NULL,
    email    varchar(45)     NOT NULL UNIQUE,
    password varchar(60)     NOT NULL,
    imagen   varchar(100) DEFAULT NULL,
    PRIMARY KEY (id)

) ENGINE = InnoDB;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO usuarios (nombre, apellido, usuario, email, password, imagen)
VALUES ('Florencia', 'Paez', 'florencia', 'Florpaez98@gmail.com',
        '$2y$10$kaSHZ21wumFfbzSJNm5Vxuc4FUWmil.8eSl4UeZGHLM70FtjnpsDC', 'imagenes/usuarios/user_img.jpg'),
       ('Pamela', 'Iglesias', 'pi', 'pamelaiglesias96@gmail.com',
        '$2y$10$kaSHZ21wumFfbzSJNm5Vxuc4FUWmil.8eSl4UeZGHLM70FtjnpsDC', 'imagenes/usuarios/user_img.jpg');

-- ----------------------------
-- Table structure for novedades
-- ----------------------------
DROP TABLE IF EXISTS novedades;
CREATE TABLE novedades
(
    id           int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    cuerpo       varchar(240),
    `date`       DATETIME,
    Usuarios_id  int(9) UNSIGNED NOT NULL,
    novedades_id int(9) UNSIGNED DEFAULT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (novedades_id) REFERENCES novedades (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ----------------------------
-- Records of novedades
-- ----------------------------
INSERT INTO novedades (cuerpo, date, Usuarios_id)
VALUES ('Que onda Doctor Sueño?', '2019-11-14 16:35:59', 1),
       ('Acabo de terminar mi saga favorita D:, que me recomiendan', '2019-11-10 01:02:15', 2);

-- ----------------------------
-- Table structure for eventos
-- ----------------------------
DROP TABLE IF EXISTS eventos;
CREATE TABLE eventos
(
    id          int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    descripcion varchar(240),
    nombre      varchar(240)    NOT NULL,
    fecha       DATE,
    hora        TIME,
    lugar       varchar(100),
    PRIMARY KEY (id)
) ENGINE = InnoDB;

-- ----------------------------
-- Records of eventos
-- ----------------------------
INSERT INTO eventos (descripcion, nombre, fecha, hora, lugar)
VALUES (NULL, 'Presentación de libros', 1, "2020-03-05", "13:00:00", "Feria del libro");

-- ----------------------------
-- Table structure for eventos_usuarios
-- ----------------------------
DROP TABLE IF EXISTS eventos_usuarios;
CREATE TABLE eventos_usuarios
(
    id          int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    eventos_id  int(9) UNSIGNED NOT NULL,
    Usuarios_id int(9) UNSIGNED NOT NULL,
    estado      tinyint(1)      NOT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (eventos_id) REFERENCES eventos (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ----------------------------
-- Records of eventos_usuarios
-- ----------------------------
INSERT INTO eventos_usuarios (eventos_id, Usuarios_id, estado)
VALUES (1, 1, 1);

-- ----------------------------
-- Table structure for comentarios
-- ----------------------------
DROP TABLE IF EXISTS comentarios;
CREATE TABLE comentarios
(
    id           int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    comentario   varchar(240)    NOT NULL,
    Novedades_id int(9) UNSIGNED NOT NULL,
    Usuarios_id  int(9) UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Novedades_id) REFERENCES novedades (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ----------------------------
-- Table structure for favoritos
-- ----------------------------
DROP TABLE IF EXISTS favoritos;
CREATE TABLE favoritos
(
    id           int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    Usuarios_id  int(9) UNSIGNED NOT NULL,
    Novedades_id int(9) UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Novedades_id) REFERENCES novedades (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ----------------------------
-- Table structure for intereses
-- ----------------------------
DROP TABLE IF EXISTS intereses;
CREATE TABLE intereses
(
    id          int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    Usuarios_id int(9) UNSIGNED NOT NULL,
    interes     varchar(100)    NOT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Usuarios_id) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ----------------------------
-- Table structure for notificaciones
-- ----------------------------
DROP TABLE IF EXISTS notificaciones;
CREATE TABLE notificaciones
(
    id                 int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    Usuarios_id_recibe int(9) UNSIGNED NOT NULL,
    Usuarios_id_envia  int(9) UNSIGNED NOT NULL,
    notificacion       varchar(100)    NOT NULL,
    Novedades_id       int(9) UNSIGNED NOT NULL,
    leida              int(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),

    FOREIGN KEY (Usuarios_id_recibe) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Usuarios_id_envia) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Novedades_id) REFERENCES novedades (id) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB;

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS tags;
CREATE TABLE tags
(
    id           int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    Novedades_id int(9) UNSIGNED NOT NULL,
    tag          varchar(100)    NOT NULL,
    PRIMARY KEY (id),

    FOREIGN KEY (Novedades_id) REFERENCES novedades (id) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB;
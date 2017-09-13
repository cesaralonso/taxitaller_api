-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2017 a las 02:10:59
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taxytaller`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chofer`
--

CREATE TABLE `chofer` (
  `idchofer` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `direccion` varchar(145) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `lat` varchar(45) DEFAULT NULL,
  `lng` varchar(45) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `licencia` varchar(45) NOT NULL,
  `edad` int(2) NOT NULL,
  `sexo` varchar(9) NOT NULL DEFAULT 'MASCULINO',
  `estado_civil` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `baja` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `choque`
--

CREATE TABLE `choque` (
  `idchoque` int(11) NOT NULL,
  `monto_por_choque` float NOT NULL,
  `fecha_choque` datetime NOT NULL,
  `fecha_pago_choque` datetime DEFAULT NULL,
  `baja` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordenada`
--

CREATE TABLE `coordenada` (
  `idcoordenada` int(11) NOT NULL,
  `lat` varchar(45) NOT NULL,
  `lng` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `baja` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion`
--

CREATE TABLE `liquidacion` (
  `idliquidacion` int(11) NOT NULL,
  `folio` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  `liquidacion_a_pagar` float NOT NULL,
  `liquidacion_pagada` float NOT NULL DEFAULT '0',
  `liquidacion_deuda` float NOT NULL,
  `liquidacion_estatus` varchar(20) NOT NULL DEFAULT 'SIN_PAGAR',
  `observaciones` varchar(345) DEFAULT NULL,
  `firma` tinyint(1) DEFAULT '0',
  `baja` tinyint(1) DEFAULT '0',
  `permiso_idpermiso` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `liquidacioncol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanico`
--

CREATE TABLE `mecanico` (
  `idmecanico` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `baja` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_vehiculo_taller`
--

CREATE TABLE `orden_vehiculo_taller` (
  `idorden_vehiculo_taller` int(11) NOT NULL,
  `folio` varchar(45) NOT NULL,
  `fecha_expedicion` datetime NOT NULL,
  `costo_total_refacciones` float NOT NULL,
  `costo_utilidad_refacciones` float NOT NULL,
  `costo_mano_obra` float NOT NULL,
  `presupuesto_cliente_refacciones` float DEFAULT NULL,
  `prct_extra_15` int(2) NOT NULL,
  `prct_extra_total` int(2) NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `baja` tinyint(1) DEFAULT '0',
  `vehiculo_taller_idvehiculo_taller` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_vehiculo_taller_has_refaccion`
--

CREATE TABLE `orden_vehiculo_taller_has_refaccion` (
  `orden_vehiculo_taller_idorden_vehiculo_taller` int(11) NOT NULL,
  `orden_vehiculo_taller_vehiculo_taller_idvehiculo_taller` int(11) NOT NULL,
  `refaccion_idrefaccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL,
  `permiso` varchar(45) NOT NULL,
  `descripcion` varchar(145) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `vigencia` datetime NOT NULL,
  `liquidacion_diaria` float NOT NULL DEFAULT '320',
  `liquidacion_domingo` float NOT NULL DEFAULT '200',
  `baja` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietario`
--

CREATE TABLE `propietario` (
  `idpropietario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(145) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `sexo` varchar(9) DEFAULT 'MASCULINO',
  `lat` varchar(45) DEFAULT NULL,
  `lng` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refaccion`
--

CREATE TABLE `refaccion` (
  `idrefaccion` int(11) NOT NULL,
  `costo` float NOT NULL DEFAULT '0',
  `utilidad` float NOT NULL DEFAULT '0',
  `precio_venta` float NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha_ingresa` datetime NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `baja` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(25) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`, `descripcion`) VALUES
(1, 'superuser', 'Usuario con todos los privilegios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taller`
--

CREATE TABLE `taller` (
  `idtaller` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `lat` varchar(45) DEFAULT NULL,
  `lng` varchar(45) DEFAULT NULL,
  `baja` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `taller`
--

INSERT INTO `taller` (`idtaller`, `nombre`, `direccion`, `descripcion`, `telefono`, `lat`, `lng`, `baja`, `created_at`, `created_by`) VALUES
(1, 'Taller Test', 'Federico del Toro #123, Col Centro', 'Taller Principal', '3314243442', '3453246534', '-3124563564', 0, '2017-09-05 00:02:25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taller_has_mecanico`
--

CREATE TABLE `taller_has_mecanico` (
  `taller_idtaller` int(11) NOT NULL,
  `mecanico_idmecanico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `password` varchar(145) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `rol_idrol` int(11) NOT NULL,
  `acceso` datetime DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `token` varchar(185) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`iduser`, `password`, `nombre`, `usuario`, `rol_idrol`, `acceso`, `email`, `token`, `token_expire`) VALUES
(2, '$2a$10$4a38738f68a42a40392fcuRMvQmFzLM7i9WHB44.ZipDQzx8Fit.i', 'César Alonso Magaña Gavilanes', '', 1, '2017-09-05 02:00:32', 'cesar_alonso_m_g@hotmail.com', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZHVzZXIiOiIyIiwibm9tYnJlIjoiQ1x1MDBlOXNhciBBbG9uc28gTWFnYVx1MDBmMWEgR2F2aWxhbmVzIiwicGFzc3dvcmQiOiIkMmEkMTAkNGEzODczOGY2OGE0MmE0MDM5MmZjdVJNdlFt', '2017-09-05 03:00:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `idvehiculo` int(11) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `modelo` varchar(45) NOT NULL,
  `anio` int(11) NOT NULL,
  `serie` varchar(45) NOT NULL,
  `placas` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `condicion_inicial` varchar(45) NOT NULL,
  `condicion_actual` varchar(45) DEFAULT NULL,
  `estaus_actividad` varchar(25) DEFAULT 'INACTIVO',
  `baja` tinyint(1) DEFAULT '0',
  `propietario_idpropietario` int(11) NOT NULL,
  `permiso_idpermiso` int(11) NOT NULL,
  `fecha_asigancion_permiso` datetime DEFAULT NULL,
  `chofer_idchofer` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo_has_coordenada`
--

CREATE TABLE `vehiculo_has_coordenada` (
  `vehiculo_idvehiculo` int(11) NOT NULL,
  `vehiculo_propietario_idpropietario` int(11) NOT NULL,
  `vehiculo_permiso_idpermiso` int(11) NOT NULL,
  `coordenada_idcoordenada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo_taller`
--

CREATE TABLE `vehiculo_taller` (
  `idvehiculo_taller` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `fecha_tentativa_salida` datetime NOT NULL,
  `observaciones_iniciales` varchar(345) NOT NULL,
  `observaciones_finales` varchar(345) DEFAULT NULL,
  `reparacion` varchar(345) DEFAULT NULL,
  `estatus` varchar(45) NOT NULL DEFAULT 'REPARANDO',
  `baja` tinyint(1) DEFAULT '0',
  `vehiculo_idvehiculo` int(11) NOT NULL,
  `vehiculo_propietario_idpropietario` int(11) NOT NULL,
  `vehiculo_permiso_idpermiso` int(11) NOT NULL,
  `taller_idtaller` int(11) NOT NULL,
  `chofer_idchofer` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `chofer`
--
ALTER TABLE `chofer`
  ADD PRIMARY KEY (`idchofer`),
  ADD UNIQUE KEY `idchofer_UNIQUE` (`idchofer`);

--
-- Indices de la tabla `choque`
--
ALTER TABLE `choque`
  ADD PRIMARY KEY (`idchoque`),
  ADD UNIQUE KEY `idchoque_UNIQUE` (`idchoque`);

--
-- Indices de la tabla `coordenada`
--
ALTER TABLE `coordenada`
  ADD PRIMARY KEY (`idcoordenada`),
  ADD UNIQUE KEY `idcoordenada_UNIQUE` (`idcoordenada`);

--
-- Indices de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD PRIMARY KEY (`idliquidacion`,`permiso_idpermiso`),
  ADD UNIQUE KEY `idliquidacion_UNIQUE` (`idliquidacion`),
  ADD KEY `fk_liquidacion_permiso1_idx` (`permiso_idpermiso`);

--
-- Indices de la tabla `mecanico`
--
ALTER TABLE `mecanico`
  ADD PRIMARY KEY (`idmecanico`),
  ADD UNIQUE KEY `idmecanico_UNIQUE` (`idmecanico`);

--
-- Indices de la tabla `orden_vehiculo_taller`
--
ALTER TABLE `orden_vehiculo_taller`
  ADD PRIMARY KEY (`idorden_vehiculo_taller`,`vehiculo_taller_idvehiculo_taller`),
  ADD UNIQUE KEY `idorden_vehiculo_taller_UNIQUE` (`idorden_vehiculo_taller`),
  ADD KEY `fk_orden_vehiculo_taller_vehiculo_taller1_idx` (`vehiculo_taller_idvehiculo_taller`);

--
-- Indices de la tabla `orden_vehiculo_taller_has_refaccion`
--
ALTER TABLE `orden_vehiculo_taller_has_refaccion`
  ADD PRIMARY KEY (`orden_vehiculo_taller_idorden_vehiculo_taller`,`orden_vehiculo_taller_vehiculo_taller_idvehiculo_taller`,`refaccion_idrefaccion`),
  ADD KEY `fk_orden_vehiculo_taller_has_refaccion_refaccion1_idx` (`refaccion_idrefaccion`),
  ADD KEY `fk_orden_vehiculo_taller_has_refaccion_orden_vehiculo_talle_idx` (`orden_vehiculo_taller_idorden_vehiculo_taller`,`orden_vehiculo_taller_vehiculo_taller_idvehiculo_taller`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`idpermiso`),
  ADD UNIQUE KEY `idpermiso_UNIQUE` (`idpermiso`);

--
-- Indices de la tabla `propietario`
--
ALTER TABLE `propietario`
  ADD PRIMARY KEY (`idpropietario`),
  ADD UNIQUE KEY `idpropietario_UNIQUE` (`idpropietario`);

--
-- Indices de la tabla `refaccion`
--
ALTER TABLE `refaccion`
  ADD PRIMARY KEY (`idrefaccion`),
  ADD UNIQUE KEY `idrefaccion_UNIQUE` (`idrefaccion`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`),
  ADD UNIQUE KEY `id_UNIQUE` (`idrol`);

--
-- Indices de la tabla `taller`
--
ALTER TABLE `taller`
  ADD PRIMARY KEY (`idtaller`),
  ADD UNIQUE KEY `idtaller_UNIQUE` (`idtaller`);

--
-- Indices de la tabla `taller_has_mecanico`
--
ALTER TABLE `taller_has_mecanico`
  ADD PRIMARY KEY (`taller_idtaller`,`mecanico_idmecanico`),
  ADD KEY `fk_taller_has_mecanico_mecanico1_idx` (`mecanico_idmecanico`),
  ADD KEY `fk_taller_has_mecanico_taller1_idx` (`taller_idtaller`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`,`rol_idrol`),
  ADD UNIQUE KEY `id_UNIQUE` (`iduser`),
  ADD KEY `fk_user_rol_idx` (`rol_idrol`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`idvehiculo`,`propietario_idpropietario`,`permiso_idpermiso`),
  ADD UNIQUE KEY `idvehiculo_UNIQUE` (`idvehiculo`),
  ADD KEY `fk_vehiculo_propietario1_idx` (`propietario_idpropietario`),
  ADD KEY `fk_vehiculo_permiso1_idx` (`permiso_idpermiso`),
  ADD KEY `fk_vehiculo_chofer1_idx` (`chofer_idchofer`);

--
-- Indices de la tabla `vehiculo_has_coordenada`
--
ALTER TABLE `vehiculo_has_coordenada`
  ADD PRIMARY KEY (`vehiculo_idvehiculo`,`vehiculo_propietario_idpropietario`,`vehiculo_permiso_idpermiso`,`coordenada_idcoordenada`),
  ADD KEY `fk_vehiculo_has_coordenada_coordenada1_idx` (`coordenada_idcoordenada`),
  ADD KEY `fk_vehiculo_has_coordenada_vehiculo1_idx` (`vehiculo_idvehiculo`,`vehiculo_propietario_idpropietario`,`vehiculo_permiso_idpermiso`);

--
-- Indices de la tabla `vehiculo_taller`
--
ALTER TABLE `vehiculo_taller`
  ADD PRIMARY KEY (`idvehiculo_taller`,`created_at`),
  ADD UNIQUE KEY `idvehiculo_taller_UNIQUE` (`idvehiculo_taller`),
  ADD KEY `fk_vehiculo_taller_vehiculo1_idx` (`vehiculo_idvehiculo`,`vehiculo_propietario_idpropietario`,`vehiculo_permiso_idpermiso`),
  ADD KEY `fk_vehiculo_taller_taller1_idx` (`taller_idtaller`),
  ADD KEY `fk_vehiculo_taller_chofer1_idx` (`chofer_idchofer`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chofer`
--
ALTER TABLE `chofer`
  MODIFY `idchofer` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `choque`
--
ALTER TABLE `choque`
  MODIFY `idchoque` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `coordenada`
--
ALTER TABLE `coordenada`
  MODIFY `idcoordenada` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  MODIFY `idliquidacion` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mecanico`
--
ALTER TABLE `mecanico`
  MODIFY `idmecanico` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `orden_vehiculo_taller`
--
ALTER TABLE `orden_vehiculo_taller`
  MODIFY `idorden_vehiculo_taller` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `propietario`
--
ALTER TABLE `propietario`
  MODIFY `idpropietario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `refaccion`
--
ALTER TABLE `refaccion`
  MODIFY `idrefaccion` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `taller`
--
ALTER TABLE `taller`
  MODIFY `idtaller` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `idvehiculo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `vehiculo_taller`
--
ALTER TABLE `vehiculo_taller`
  MODIFY `idvehiculo_taller` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD CONSTRAINT `fk_liquidacion_permiso1` FOREIGN KEY (`permiso_idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_vehiculo_taller`
--
ALTER TABLE `orden_vehiculo_taller`
  ADD CONSTRAINT `fk_orden_vehiculo_taller_vehiculo_taller1` FOREIGN KEY (`vehiculo_taller_idvehiculo_taller`) REFERENCES `vehiculo_taller` (`idvehiculo_taller`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_vehiculo_taller_has_refaccion`
--
ALTER TABLE `orden_vehiculo_taller_has_refaccion`
  ADD CONSTRAINT `fk_orden_vehiculo_taller_has_refaccion_orden_vehiculo_taller1` FOREIGN KEY (`orden_vehiculo_taller_idorden_vehiculo_taller`,`orden_vehiculo_taller_vehiculo_taller_idvehiculo_taller`) REFERENCES `orden_vehiculo_taller` (`idorden_vehiculo_taller`, `vehiculo_taller_idvehiculo_taller`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orden_vehiculo_taller_has_refaccion_refaccion1` FOREIGN KEY (`refaccion_idrefaccion`) REFERENCES `refaccion` (`idrefaccion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `taller_has_mecanico`
--
ALTER TABLE `taller_has_mecanico`
  ADD CONSTRAINT `fk_taller_has_mecanico_mecanico1` FOREIGN KEY (`mecanico_idmecanico`) REFERENCES `mecanico` (`idmecanico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_taller_has_mecanico_taller1` FOREIGN KEY (`taller_idtaller`) REFERENCES `taller` (`idtaller`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_rol` FOREIGN KEY (`rol_idrol`) REFERENCES `rol` (`idrol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `fk_vehiculo_chofer1` FOREIGN KEY (`chofer_idchofer`) REFERENCES `chofer` (`idchofer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_permiso1` FOREIGN KEY (`permiso_idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_propietario1` FOREIGN KEY (`propietario_idpropietario`) REFERENCES `propietario` (`idpropietario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculo_has_coordenada`
--
ALTER TABLE `vehiculo_has_coordenada`
  ADD CONSTRAINT `fk_vehiculo_has_coordenada_coordenada1` FOREIGN KEY (`coordenada_idcoordenada`) REFERENCES `coordenada` (`idcoordenada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_has_coordenada_vehiculo1` FOREIGN KEY (`vehiculo_idvehiculo`,`vehiculo_propietario_idpropietario`,`vehiculo_permiso_idpermiso`) REFERENCES `vehiculo` (`idvehiculo`, `propietario_idpropietario`, `permiso_idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculo_taller`
--
ALTER TABLE `vehiculo_taller`
  ADD CONSTRAINT `fk_vehiculo_taller_chofer1` FOREIGN KEY (`chofer_idchofer`) REFERENCES `chofer` (`idchofer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_taller_taller1` FOREIGN KEY (`taller_idtaller`) REFERENCES `taller` (`idtaller`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vehiculo_taller_vehiculo1` FOREIGN KEY (`vehiculo_idvehiculo`,`vehiculo_propietario_idpropietario`,`vehiculo_permiso_idpermiso`) REFERENCES `vehiculo` (`idvehiculo`, `propietario_idpropietario`, `permiso_idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

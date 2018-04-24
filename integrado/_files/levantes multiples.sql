SELECT * 
FROM inventario_movimientos im
LEFT JOIN (SELECT MAX(num_levante ) AS num_levante,MAX(grupo) AS grupo  FROM inventario_declaraciones GROUP BY num_levante,grupo) id ON im.num_levante = id.num_levante
WHERE im.cod_maestro=4184
AND inventario_entrada=2462

SELECT orden, doc_tte, doc_tte AS doc_tte_aux, inventario_entrada, inventario_entrada AS item, arribo, nombre_referencia, cod_referencia, codigo_referencia, cant_declaraciones, cantidad, peso, valor, modelo, SUM(peso_nonac) AS peso_nonac, SUM(peso_naci) AS peso_naci, SUM(cantidad_naci) AS cantidad_naci, SUM(cantidad_nonac) AS cantidad_nonac, SUM(fob_nonac) AS fob_nonac, SUM(cif) AS cif, cod_maestro, MIN(num_levante) AS num_levante, un_grupo, numero_documento, razon_social FROM (SELECT im.codigo, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN 1 ELSE 0 END AS movimiento, do_asignados.do_asignado AS orden, do_asignados.doc_tte AS doc_tte, ie.arribo, ref.nombre AS nombre_referencia, ref.codigo_ref AS cod_referencia, ref.codigo AS codigo_referencia, ie.cant_declaraciones, ie.cantidad AS cantidad, ie.peso AS peso, ie.valor AS valor, ie.modelo AS modelo, im.inventario_entrada, im.cod_maestro, im.num_levante, im.tipo_movimiento, id.grupo AS un_grupo, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN peso_nonac ELSE 0 END AS peso_nonac, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN peso_naci ELSE 0 END AS peso_naci, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN cantidad_naci ELSE 0 END AS cantidad_naci, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN cantidad_nonac ELSE 0 END AS cantidad_nonac, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN fob_nonac ELSE 0 END AS fob_nonac, CASE WHEN im.tipo_movimiento IN(1,2,3,7,10,15,30) THEN cif ELSE 0 END AS cif, numero_documento, razon_social FROM do_asignados, inventario_entradas ie,arribos,clientes,referencias ref,inventario_movimientos im LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante WHERE im.inventario_entrada = ie.codigo AND arribos.arribo = ie.arribo AND arribos.orden = do_asignados.do_asignado AND clientes.numero_documento = do_asignados.por_cuenta AND ie.referencia = ref.codigo AND ie.cantidad > 0 AND im.cod_maestro='4184' ) AS inv 


GROUP BY num_levante ORDER BY un_grupo


SELECT * 
FROM inventario_movimientos im
LEFT JOIN inventario_declaraciones  id ON im.num_levante = id.num_levante
WHERE im.cod_maestro=4184
AND inventario_entrada=2462




INSERT INTO `nbysoft_db`.`inventario_tipos_movimiento` (
`codigo` ,
`nombre` ,
`descripcion` ,
`tipo_sede` ,
`tipo_move` ,
`cdtipo` ,
`zona_franca`
)
VALUES (
'18', 'Retiro de Acondicionados', NULL , '3', '3', '', '222'
CREATE TABLE `teste_brain`.`chamados` (`id` INT NOT NULL AUTO_INCREMENT ,
`incidente` VARCHAR(50) NOT NULL , `descricao` VARCHAR(500) NOT NULL ,
`anexo` LONGBLOB NOT NULL , `usuario_responsavel` INT NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `chamados` ADD FOREIGN KEY (`usuario_responsavel`) 
    REFERENCES `usuarios`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `setores` (`id` INT NOT NULL AUTO_INCREMENT ,
`nome` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `usuarios` ADD FOREIGN KEY (`setor`) REFERENCES `setores`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `chamados` CHANGE `anexo` `anexo` LONGBLOB NULL;

ALTER TABLE `chamados` ADD `comentario` VARCHAR(500) NULL AFTER `usuario_responsavel`;

ALTER TABLE `usuarios` ADD `login` VARCHAR(100) NOT NULL AFTER `setor`, ADD `senha` VARCHAR(100) NOT NULL AFTER `login`;
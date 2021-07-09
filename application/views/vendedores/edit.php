
<?php $this->load->view('layout/sidebar'); ?>


    <!-- Main Content -->
    <div id="content">

        <?php $this->load->view('layout/navbar'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendedores'); ?>">Vendedores</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
                </ol>
              </nav>

            <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    
                    <div class="card-body">
                        <form method="POST" name="form_edit">
                            <p><strong><i class="fas fa-clock"></i>&nbsp; &nbsp;Útima alteração: &nbsp; </strong><?php echo formata_data_banco_com_hora($vendedor->vendedor_data_alteracao); ?></p>
                            <fieldset class="mt-4 border p-2">
                                
                                <legend class="font-small"><i class="fas fa-user-tag"></i>&nbsp;Dados principais</legend>
                                <div class="form-group row mb-3">

                                    <div class="col-md-6 mb-3">
                                        <label>Nome completo</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_nome_completo" placeholder="Nome completo vendedor" value="<?php echo $vendedor->vendedor_nome_completo; ?>">
                                        <?php echo form_error('vendedor_nome_completo', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>CPF</label>
                                        <input type="text" class="form-control form-control-user cpf" name="vendedor_cpf" placeholder="CPF" value="<?php echo $vendedor->vendedor_cpf; ?>">
                                        <?php echo form_error('vendedor_cpf', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label>RG</label>
                                        <input type="text" class="form-control form-control-user rg" name="vendedor_rg" placeholder="RG" value="<?php echo $vendedor->vendedor_rg; ?>">
                                        <?php echo form_error('vendedor_rg', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group row mb-3">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label>E-mail</label>
                                        <input type="email" class="form-control form-control-user " name="vendedor_email" placeholder="E-mail do vendedor" value="<?php echo $vendedor->vendedor_email; ?>">
                                        <?php echo form_error('vendedor_email', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label>Telefone fixo</label>
                                        <input type="text" class="form-control form-control-user sp_celphones" name="vendedor_telefone" placeholder="Telefone fixo" value="<?php echo $vendedor->vendedor_telefone; ?>">
                                        <?php echo form_error('vendedor_telefone', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label>Telefone celular</label>
                                        <input type="text" class="form-control form-control-user sp_celphones" name="vendedor_celular" placeholder="Telefone fixo" value="<?php echo $vendedor->vendedor_celular; ?>">
                                        <?php echo form_error('vendedor_celular', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                </div>
                                  
                            </fieldset>
                            
                            <fieldset class="mt-4 border p-2">
                                
                                <legend class="font-small"><i class="fas fa-map-marker-alt"></i>&nbsp;Dados de endereço</legend>
                                <div class="form-group row mb-3">
                                    
                                    <div class="col-md-6">
                                        <label>Endereço</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_endereco" placeholder="Endereço"  value="<?php echo $vendedor->vendedor_endereco; ?>">
                                        <?php echo form_error('vendedor_endereco', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label>Número</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_numero_endereco" placeholder="Número"  value="<?php echo $vendedor->vendedor_numero_endereco; ?>">
                                        <?php echo form_error('vendedor_numero_endereco', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>Complemento</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_complemento" placeholder="Complemento"  value="<?php echo $vendedor->vendedor_complemento; ?>">
                                        <?php echo form_error('vendedor_complemento', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row mb-3">
                                    <div class="col-md-4">
                                        <label>Bairro</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_bairro" placeholder="Bairro"  value="<?php echo $vendedor->vendedor_bairro; ?>">
                                        <?php echo form_error('vendedor_bairro', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label>CEP</label>
                                        <input type="text" class="form-control form-control-user cep" name="vendedor_cep" placeholder="CEP" value="<?php echo $vendedor->vendedor_cep; ?>">
                                        <?php echo form_error('vendedor_cep', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-5">
                                        <label>Cidade</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_cidade" placeholder="Cidade" value="<?php echo $vendedor->vendedor_cidade; ?>">
                                        <?php echo form_error('vendedor_cidade', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                
                                    <div class="col-md-1">
                                        <label>UF</label>
                                        <input type="text" class="form-control form-control-user uf" name="vendedor_estado" placeholder="UF"  value="<?php echo $vendedor->vendedor_estado; ?>">
                                        <?php echo form_error('vendedor_estado', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <fieldset class="mt-4 border p-2">
                                
                                <legend class="font-small"><i class="fas fa-tools"></i>&nbsp;Configurações</legend>
                            
                                <div class="form-group row">
                                    
                                    <div class="col-md-3">
                                        <label>Vendedor Ativo</label>
                                        <select class="custom-select" name="vendedor_ativo">
                                            <option value="0" <?php echo ($vendedor->vendedor_ativo == 0 ? 'selected' : ''); ?> >Não</option>
                                            <option value="1" <?php echo ($vendedor->vendedor_ativo == 1 ? 'selected' : ''); ?> >Sim</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label>Matrícula</label>
                                        <input type="text" class="form-control form-control-user" name="vendedor_codigo" value="<?php echo $vendedor->vendedor_codigo; ?>" readonly="">
                                        <?php echo form_error('vendedor_cidade', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label>Observação</label>
                                        <textarea class="form-control form-control-user" name="vendedor_obs" placeholder="Observação"><?php echo $vendedor->vendedor_obs; ?></textarea>
                                        <?php echo form_error('vendedor_obs', '<small id="emailHelp" class="form-text text-danger">','</small>'); ?>

                                    </div>
                                
                                </div>
                            
                            </fieldset>
                            
                            
                            <div class="form-group row mb-3">
                                
                                <input type="hidden" name="vendedor_id" value="<?php echo $vendedor->vendedor_id; ?>"/>
                                
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                            <a title="Voltar" href="<?php echo base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2" ></i>&nbsp;Voltar</a>
                        </form>
                    </div>
                </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

 
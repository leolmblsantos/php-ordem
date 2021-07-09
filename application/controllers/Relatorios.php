<?php

defined('BASEPATH') OR exit('Ação não permitida');

class Relatorios extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        if (!$this->ion_auth->logged_in()){
            $this->session->set_flashdata('info', 'Sua sessão expirou!');
            redirect('login');
        }
        
        if(!$this->ion_auth->is_admin()){
            $this->session->set_flashdata('info', 'Você não tem permissão para acessar o menu relatório');
            redirect('/');
        }
    }
    
    public function vendas() {
        
        $data = array(
            'titulo' => 'Relatório de vendas', 
        );
        
        $data_inicial = $this->input->post('data_inicial');
        $data_final = $this->input->post('data_final');
        
        if($data_inicial){
            
            $this->load->model('vendas_model');
            
            if($this->vendas_model->gerar_relatorio_vendas($data_inicial, $data_final)){
                
                //Montar o PDF
                
                $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));
            
                $vendas = $this->vendas_model->gerar_relatorio_vendas($data_inicial, $data_final);

                $file_name = 'Relatório de venda&nbsp;';


                //Início do HTML
                $html = '<html>';

                $html .= '<head>';

                    $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de devenda</title>';


                $html .= '</head>';

                $html .= '<body style="font-size: 12px">';

                $html .= '<h4 align="center">
                            ' . $empresa->sistema_razao_social . ' <br/>
                            ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                            ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                            ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                            ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                            ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                        </h4>';

                $html .= '<hr>';

                
                
                
                
                if($data_inicial && $data_final){
                    
                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
                    
                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
                    
                }else{
                    
                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
                    
                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
                }

//                $html .= '<p>'
//                        . '<strong>Cliente: </strong>' . $venda->cliente_nome_completo . '<br/>'
//                        . '<strong>CPF: </strong>' . $venda->cliente_cpf_cnpj . '<br/>'
//                        . '<strong>Celular: </strong>' . $venda->cliente_celular . '<br/>'
//                        . '<strong>Data de emissão: </strong>' . formata_data_banco_com_hora($venda->venda_data_emissao) . '<br/>'
//                        . '<strong>Forma de Pagamento: </strong>' . $venda->forma_pagamento .'<br/>'
//                        . '</p>';

                $html .= '<hr>';

                // Dados da ordem


                $html .= '<table width="100%" border: solid #ddd 1px>';
                    $html .= '<tr>';
                        $html .= '<th>Venda</th>';
                        $html .= '<th>Data</th>';
                        $html .= '<th>Cliente</th>';
                        $html .= '<th>Forma de pagamento</th>';
                        $html .= '<th>Valor Total</th>';

                    $html .= '</tr>';


                    $valor_final_venda = $this->vendas_model->get_valor_final_relatorio($data_inicial, $data_final);

                    foreach ($vendas as $venda) :

                        $html .= '<tr>';

                            $html .= '<td>' . $venda->venda_id .'</td>';
                            $html .= '<td>' . formata_data_banco_com_hora($venda->venda_data_emissao) .'</td>';
                            $html .= '<td>' . $venda->cliente_nome_completo.'</td>';
                            $html .= '<td>' . $venda->forma_pagamento.'</td>';
                            $html .= '<td>' . 'R$&nbsp;' .$venda->venda_valor_total.'</td>';

                        $html .= '</tr>';

                    endforeach;

                    $html .= '<th colspan="3">';

                        $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                        $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_venda->venda_valor_total .' </td>';

                    $html .= '</th>';

                $html .= '</table>';

                $html .= '</body>';

                $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();


                //False -> abre PDF no navegador
                //True -> faz o download

                $this->pdf->createPDF($html, $file_name, false);
            }else{
                
                if(!empty($data_inicial) && !empty($data_final)){
                    $this->session->set_flashdata('info', 'Não foram encontradas vendas entre no período de: '.formata_data_banco_sem_hora($data_inicial). '&nbsp;à&nbsp;'. formata_data_banco_sem_hora($data_final));
                }else{
                    $this->session->set_flashdata('info', 'Não foram encontradas vendas a partir de: '.formata_data_banco_sem_hora($data_inicial));
                }
                redirect('relatorios/vendas');
            }
        }
        
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/vendas');
        $this->load->view('layout/footer');
 
    }
    
    public function os() {
        
        $data = array(
            'titulo' => 'Relatório de ordens de serviços', 
        );
        
        $data_inicial = $this->input->post('data_inicial');
        $data_final = $this->input->post('data_final');
        
        if($data_inicial){
            
            $this->load->model('ordem_servico_model');
            
            if($this->ordem_servico_model->gerar_relatorio_os($data_inicial, $data_final)){
                
                //Montar o PDF
                
                $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));
            
                $ordens_servicos = $this->ordem_servico_model->gerar_relatorio_os($data_inicial, $data_final);

                $file_name = 'Relatório de ordem de serviços';


                //Início do HTML
                $html = '<html>';

                $html .= '<head>';

                    $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de ordens de serviços</title>';


                $html .= '</head>';

                $html .= '<body style="font-size: 12px">';

                $html .= '<h4 align="center">
                            ' . $empresa->sistema_razao_social . ' <br/>
                            ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                            ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                            ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                            ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                            ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                        </h4>';

                $html .= '<hr>';
                
                if($data_inicial && $data_final){
                    
                    $html .= '<p align="center" style="font-size: 12px">Relatório de ordens de serviços realizadas entre:</p>';
                    
                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
                    
                }else{
                    
                    $html .= '<p align="center" style="font-size: 12px">Relatório de ordens de serviços realizadas apartir da de:</p>';
                    
                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
                }

                $html .= '<hr>';

                // Dados da ordem


                $html .= '<table width="100%" border: solid #ddd 1px>';
                    $html .= '<tr>';
                        $html .= '<th>Ordem ID</th>';
                        $html .= '<th>Data</th>';
                        $html .= '<th>Cliente</th>';
                        $html .= '<th>Forma de pagamento</th>';
                        $html .= '<th>Valor Total</th>';

                    $html .= '</tr>';

                    $valor_final_os = $this->ordem_servico_model->get_valor_final_relatorio_os($data_inicial, $data_final);

                    foreach ($ordens_servicos as $os) :

                        $html .= '<tr>';

                            $html .= '<td>' . $os->ordem_servico_id .'</td>';
                            $html .= '<td>' . formata_data_banco_com_hora($os->ordem_servico_data_emissao) .'</td>';
                            $html .= '<td>' . $os->cliente_nome_completo.'</td>';
                            $html .= '<td>' . $os->forma_pagamento.'</td>';
                            $html .= '<td>' . 'R$&nbsp;' .$os->ordem_servico_valor_total.'</td>';

                        $html .= '</tr>';

                    endforeach;

                    $html .= '<th colspan="3">';

                        $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                        $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_os->ordem_servico_valor_total .' </td>';

                    $html .= '</th>';

                $html .= '</table>';

                $html .= '</body>';

                $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();


                //False -> abre PDF no navegador
                //True -> faz o download

                $this->pdf->createPDF($html, $file_name, false);
            }else{
                
                if(!empty($data_inicial) && !empty($data_final)){
                    $this->session->set_flashdata('info', 'Não foram encontradas ordens de serviços entre no período de: '.formata_data_banco_sem_hora($data_inicial). '&nbsp;à&nbsp;'. formata_data_banco_sem_hora($data_final));
                }else{
                    $this->session->set_flashdata('info', 'Não foram encontradas ordens de serviços a partir de: '.formata_data_banco_sem_hora($data_inicial));
                }
                redirect('relatorios/os');
            }
        }
        
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/os');
        $this->load->view('layout/footer');
    }
    
    public function receber() {
        
        $data = array(
            'titulo' => 'Relatório de contas a receber', 
        );
        
        $contas = $this->input->post('contas');
        
        if($contas == 'vencidas' || $contas == 'pagas' || $contas == 'receber'){
            
            $this->load->model('financeiro_model');
            
            if($contas == 'vencidas'){
                
                $contas_receber_status = 0;
                
                $data_vencimento = TRUE;
                
                if($this->financeiro_model->get_contas_receber_relatorio($contas_receber_status, $data_vencimento)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_receber_relatorio($contas_receber_status, $data_vencimento);

                    $file_name = 'Relatório de contas vencidas';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas vencidas</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data vencimento</th>';
                            $html .= '<th>Cliente</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_receber_id .'</td>';
                                $html .= '<td>' . formata_data_banco_com_hora($conta->conta_receber_data_vencimento) .'</td>';
                                $html .= '<td>' . $conta->cliente_nome_completo.'</td>';
                                $html .= '<td>Vencida</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_receber_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_receber_relatorio($contas_receber_status, $data_vencimento);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_receber_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas vencidas na base de dados!');
                    redirect('relatorios/receber');
                }
            }
            
            if($contas == 'pagas'){
                
                $contas_receber_status = 1;
                
                
                if($this->financeiro_model->get_contas_receber_relatorio($contas_receber_status)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_receber_relatorio($contas_receber_status);

                    $file_name = 'Relatório de contas pagas';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas pagas</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data pagamento</th>';
                            $html .= '<th>Cliente</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_receber_id .'</td>';
                                $html .= '<td>' . formata_data_banco_com_hora($conta->conta_receber_data_pagamento) .'</td>';
                                $html .= '<td>' . $conta->cliente_nome_completo.'</td>';
                                $html .= '<td>Paga</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_receber_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_receber_relatorio($contas_receber_status);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_receber_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas pagas na base de dados!');
                    redirect('relatorios/receber');
                }
            }
            
            if($contas == 'receber'){
                
                $contas_receber_status = 0;
                
                
                if($this->financeiro_model->get_contas_receber_relatorio($contas_receber_status)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_receber_relatorio($contas_receber_status);

                    $file_name = 'Relatório de contas a receber';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas pagas</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data pagamento</th>';
                            $html .= '<th>Cliente</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_receber_id .'</td>';
                                $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_receber_data_vencimento) .'</td>';
                                $html .= '<td>' . $conta->cliente_nome_completo.'</td>';
                                $html .= '<td>A receber</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_receber_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_receber_relatorio($contas_receber_status);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_receber_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas a receber na base de dados!');
                    redirect('relatorios/receber');
                }
            }
            
            //pagas
            
        }
        
        //fazer verificaçoes
        
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/receber');
        $this->load->view('layout/footer');
    }
    
    public function pagar() {
        
        $data = array(
            'titulo' => 'Relatório de contas a pagar', 
        );
        
        $contas = $this->input->post('contas');
        
        if($contas == 'pagas' || $contas == 'vencidas' || $contas == 'a_pagar'){
            
            $this->load->model('financeiro_model');
            
            if($contas == 'vencidas'){
                
                $contas_pagar_status = 0;
                
                $data_vencimento = TRUE;
                
                if($this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status, $data_vencimento)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status, $data_vencimento);

                    $file_name = 'Relatório de contas vencidas';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas vencidas</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data vencimento</th>';
                            $html .= '<th>Fornecedor</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_pagar_id .'</td>';
                                $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_pagar_data_vencimento) .'</td>';
                                $html .= '<td>' . $conta->fornecedor_nome_fantasia.'</td>';
                                $html .= '<td>Vencida</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_pagar_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_pagar_relatorio($contas_pagar_status, $data_vencimento);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas vencidas na base de dados!');
                    redirect('relatorios/pagar');
                }
            }
            
            if($contas == 'pagas'){
                
                $contas_pagar_status = 1;
                
                $data_vencimento = FALSE;
                
                if($this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status, $data_vencimento)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status);

                    $file_name = 'Relatório de contas pagas';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas pagas</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data pagamento</th>';
                            $html .= '<th>Fornecedor</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_pagar_id .'</td>';
                                $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_pagar_data_pagamento) .'</td>';
                                $html .= '<td>' . $conta->fornecedor_nome_fantasia.'</td>';
                                $html .= '<td>Paga</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_pagar_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_pagar_relatorio($contas_pagar_status);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas pagas na base de dados!');
                    redirect('relatorios/pagar');
                }
            }
            
            if($contas == 'a_pagar'){
                
                $contas_pagar_status = 0;
                
                $data_vencimento = FALSE;
                
                if($this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status, $data_vencimento)){
                    
                    //Formar o PDF
                
                    $empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

                    $contas = $this->financeiro_model->get_contas_pagar_relatorio($contas_pagar_status);

                    $file_name = 'Relatório de contas a pagar';


                    //Início do HTML
                    $html = '<html>';

                    $html .= '<head>';

                        $html .= '<title>'.$empresa->sistema_nome_fantasia.' | Relatório de contas a pagar</title>';


                    $html .= '</head>';

                    $html .= '<body style="font-size: 12px">';

                    $html .= '<h4 align="center">
                                ' . $empresa->sistema_razao_social . ' <br/>
                                ' . 'CNPJ: ' . $empresa->sistema_cnpj . ' <br/>
                                ' . $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero . ' <br/>
                                ' . 'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade . ', &nbsp;'. $empresa->sistema_estado . ' <br/>
                                ' . 'Telefone: ' . $empresa->sistema_telefone_fixo . ' <br/>
                                ' . 'E-mail: ' . $empresa->sistema_email . ' <br/>
                            </h4>';

    //                $html .= '<hr>';
    //                //sera utilizado se for usar tbm por data
    //                
    //                if($data_inicial && $data_final){
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas entre:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).' - ' .formata_data_banco_sem_hora($data_final). '</p>';
    //                }else{
    //                    $html .= '<p align="center" style="font-size: 12px">Relatório de vendas realizadas apartir da de:</p>';
    //                    $html .= '<p align="center" p style="font-size: 12px">'. formata_data_banco_sem_hora($data_inicial).'</p>';
    //                }

                    $html .= '<hr>';

                    // Dados da ordem


                    $html .= '<table width="100%" border: solid #ddd 1px>';
                        $html .= '<tr>';
                            $html .= '<th>Contas ID</th>';
                            $html .= '<th>Data vencimento</th>';
                            $html .= '<th>Fornecedor</th>';
                            $html .= '<th>Situação</th>';
                            $html .= '<th>Valor Total</th>';

                        $html .= '</tr>';

                        foreach ($contas as $conta) :

                            $html .= '<tr>';

                                $html .= '<td>' . $conta->conta_pagar_id .'</td>';
                                $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_pagar_data_vencimento) .'</td>';
                                $html .= '<td>' . $conta->fornecedor_nome_fantasia.'</td>';
                                $html .= '<td>A pagar</td>';
                                $html .= '<td>' . 'R$&nbsp;' .$conta->conta_pagar_valor.'</td>';

                            $html .= '</tr>';

                        endforeach;

                        $valor_final_contas = $this->financeiro_model->get_sum_contas_pagar_relatorio($contas_pagar_status);

                        $html .= '<th colspan="3">';

                            $html .= '<td style="border-top: solid #add 1px"><strong>Valor Final</strong></td>';
                            $html .= '<td style="border-top: solid #add 1px">'.'R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total .' </td>';

                        $html .= '</th>';

                    $html .= '</table>';

                    $html .= '</body>';

                    $html .= '</html>';

//                    echo '<pre>';
//                    print_r($html);
//                    exit();

                    //False -> abre PDF no navegador
                    //True -> faz o download

                    $this->pdf->createPDF($html, $file_name, false);
                    
                }else{
                    
                    $this->session->set_flashdata('info', 'Não existem contas a pagar na base de dados!');
                    redirect('relatorios/pagar');
                }
            }
        }
        
        //fazer verificaçoes
        
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/pagar');
        $this->load->view('layout/footer');
    }
}
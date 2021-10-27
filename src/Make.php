<?php

/**
 * Classe a construção do xml da NFe modelo 55 e modelo 65
 * Esta classe basica está estruturada para montar XML da NFe para o
 * layout versão 4.00, os demais modelos serão derivados deste
 *
 * @category  API
 * @package   NFePHP\NFe\
 * @copyright Copyright (c) 2008-2020
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

namespace NFePHP\NFe;

use NFePHP\Common\Keys;
use NFePHP\Common\Strings;
use NFePHP\Common\DOMImproved as Dom;

class Make
{

    use
        /* Informações do Destinatário */
        Make\Dest\Dest,
        Make\Dest\EnderDest,
        Make\Dest\Entrega,
        Make\Dest\Retirada,

        /* Detalhamento de Produtos e Serviços */
        Make\Det\Adi,
        Make\Det\Arma,
        Make\Det\CEST,
        Make\Det\Comb,
        Make\Det\DetExport,
        Make\Det\DetExportInd,
        Make\Det\DI,
        Make\Det\II,
        Make\Det\Imposto,
        Make\Det\ImpostoDevol,
        Make\Det\InfAdProd,
        Make\Det\Med,
        Make\Det\NVE,
        Make\Det\Prod,
        Make\Det\Rastro,
        Make\Det\RECOPI,
        Make\Det\VeicProd,

        /* Impostos ICMS */
        Make\ICMS\ICMS,
        Make\ICMS\ICMSPart,
        Make\ICMS\ICMSSN,
        Make\ICMS\ICMSST,
        Make\ICMS\ICMSUFDest,

        /* Informações adicionais da NF-e */
        Make\InfAdic\InfAdic,
        Make\InfAdic\InfRespTec,
        Make\InfAdic\ObsCont,
        Make\InfAdic\ObsFisco,

        /* Informações da NF-e */
        Make\InfNFe\AutXML,
        Make\InfNFe\Cana,
        Make\InfNFe\Compra,
        Make\InfNFe\Exporta,
        Make\InfNFe\InfNFe,
        Make\InfNFe\Intermed,
        Make\InfNFe\NFref,
        Make\InfNFe\ProcRef,

        /* Informações de IPI (Imposto de Produtos Industrializados) */
        Make\IPI\IPI,

        /* Impostos ISS */
        Make\ISSQN\ISSQN,

        /* Pagamentos */
        Make\Pagamento\DetPag,
        Make\Pagamento\Fat,
        Make\Pagamento\Pag,

        /* Impostos PIS e COFINS */
        Make\PISCOFINS\PIS,
        Make\PISCOFINS\COFINS,
        Make\PISCOFINS\PISST,
        Make\PISCOFINS\COFINSST,

        /* Totais */
        Make\Total\ISSQNtot,
        Make\Total\ICMSTot,
        Make\Total\RetTrib,

        /* Transportes */
        Make\Transporte\Balsa,
        Make\Transporte\Lacres,
        Make\Transporte\Reboque,
        Make\Transporte\RetTransp,
        Make\Transporte\Transp,
        Make\Transporte\Transporta,
        Make\Transporte\Vagao,
        Make\Transporte\VeicTransp,
        Make\Transporte\Vol;

    /**
     * @var array
     */
    public $errors = [];
    /**
     * @var string
     */
    public $xml;

    /**
     * @var \stdClass
     */
    public $stdTot;
    /**
     * @var \stdClass
     */
    protected $stdISSQNTot;
    /**
     * @var integer
     */
    protected $mod = 55;
    /**
     * @var integer
     */
    protected $tpAmb = 2;

    /**
     * @var \NFePHP\Common\DOMImproved
     */
    public $dom;

    /**
     * @var \DOMElement
     */
    protected $NFe;
    /**
     * @var \DOMElement
     */
    protected $ide;
    /**
     * @var \DOMElement
     */
    protected $emit;
    /**
     * @var \DOMElement
     */
    protected $enderEmit;

    /**
     * @var \DOMElement
     */
    protected $total;

    /**
     * @var \DOMElement
     */
    protected $infNFeSupl;

    /**
     * @var array<\DOMElement>
     */
    protected $aDet = [];
    /**
     * @var string
     */
    protected $csrt;
    /**
     * @var boolean
     */
    protected $replaceAccentedChars = false;

    /**
     * Função construtora cria um objeto DOMDocument
     * que será carregado com o documento fiscal
     */
    public function __construct()
    {
        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;

        // elemento totalizador
        $this->stdTot = new \stdClass();
        $this->stdTot->vBC = 0;
        $this->stdTot->vICMS = 0;
        $this->stdTot->vICMSDeson = 0;
        $this->stdTot->vFCP = 0;
        $this->stdTot->vFCPUFDest = 0;
        $this->stdTot->vICMSUFDest = 0;
        $this->stdTot->vICMSUFRemet = 0;
        $this->stdTot->vBCST = 0;
        $this->stdTot->vST = 0;
        $this->stdTot->vFCPST = 0;
        $this->stdTot->vFCPSTRet = 0;
        $this->stdTot->vProd = 0;
        $this->stdTot->vFrete = 0;
        $this->stdTot->vSeg = 0;
        $this->stdTot->vDesc = 0;
        $this->stdTot->vII = 0;
        $this->stdTot->vIPI = 0;
        $this->stdTot->vIPIDevol = 0;
        $this->stdTot->vPIS = 0;
        $this->stdTot->vCOFINS = 0;
        $this->stdTot->vPISST = 0;
        $this->stdTot->vCOFINSST = 0;
        $this->stdTot->vOutro = 0;
        $this->stdTot->vNF = 0;
        $this->stdTot->vTotTrib = 0;

        // elemento totalizador de serviços
        $this->stdISSQNTot = new \stdClass();
        $this->stdISSQNTot->vServ = null;
        $this->stdISSQNTot->vBC = null;
        $this->stdISSQNTot->vISS = null;
        $this->stdISSQNTot->vPIS = null;
        $this->stdISSQNTot->vCOFINS = null;
        $this->stdISSQNTot->dCompet = null;
        $this->stdISSQNTot->vDeducao = null;
        $this->stdISSQNTot->vOutro = null;
        $this->stdISSQNTot->vDescIncond = null;
        $this->stdISSQNTot->vDescCond = null;
        $this->stdISSQNTot->vISSRet = null;
        $this->stdISSQNTot->cRegTrib = null;
    }

    /**
     * Retorna os erros detectados
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set character convertion to ASCII only ou not
     * @param bool $option
     */
    public function setOnlyAscii($option = false)
    {
        $this->replaceAccentedChars = $option;
    }

    /**
     * Returns xml string and assembly it is necessary
     * @return string
     */
    public function getXML()
    {
        if (empty($this->xml)) {
            $this->montaNFe();
        }
        return $this->xml;
    }

    /**
     * Retorns the key number of NFe (44 digits)
     * @return string
     */
    public function getChave()
    {
        return $this->chNFe;
    }

    /**
     * Returns the model of NFe 55 or 65
     * @return int
     */
    public function getModelo()
    {
        return $this->mod;
    }

    /**
     * Call method of xml assembly. For compatibility only.
     * @return string
     */
    public function montaNFe()
    {
        return $this->monta();
    }

    /**
     * NFe xml mount method
     * this function returns TRUE on success or FALSE on error
     * The xml of the NFe must be retrieved by the getXML() function or
     * directly by the public property $xml
     *
     * @return string
     * @throws \RuntimeException
     */
    public function monta()
    {
        if (!empty($this->errors)) {
            $this->errors = array_merge($this->errors, $this->dom->errors);
        } else {
            $this->errors = $this->dom->errors;
        }
        //cria a tag raiz da Nfe
        $this->buildNFe();
        //processa nfeRef e coloca as tags na tag ide
        foreach ($this->aNFref as $nfeRef) {
            $this->dom->appChild($this->ide, $nfeRef, 'Falta tag "ide"');
        }
        //monta as tags det e coloca no array $this->aDet com os detalhes dos produtos
        $this->buildDet();
        //[2] tag ide (5 B01)
        $this->dom->appChild($this->infNFe, $this->ide, 'Falta tag "infNFe"');
        //[8] tag emit (30 C01)
        $this->dom->appChild($this->infNFe, $this->emit, 'Falta tag "infNFe"');
        //[10] tag dest (62 E01)
        $this->dom->appChild($this->infNFe, $this->dest, 'Falta tag "infNFe"');
        //[12] tag retirada (80 F01)
        $this->dom->appChild($this->infNFe, $this->retirada, 'Falta tag "infNFe"');
        //[13] tag entrega (89 G01)
        $this->dom->appChild($this->infNFe, $this->entrega, 'Falta tag "infNFe"');
        //[14] tag autXML (97a.1 G50)
        foreach ($this->aAutXML as $aut) {
            $this->dom->appChild($this->infNFe, $aut, 'Falta tag "infNFe"');
        }
        //[14a] tag det (98 H01)
        foreach ($this->aDet as $det) {
            $this->dom->appChild($this->infNFe, $det, 'Falta tag "infNFe"');
        }
        //força a construção do total
        $this->total = $this->dom->createElement("total");
        $this->tagISSQNTot($this->stdISSQN);
        $this->tagICMSTot($this->stdICMSTot);
        $this->dom->appChild($this->total, $this->ICMSTot, 'Falta tag "total"');
        $this->dom->appChild($this->total, $this->ISSQNTot, 'Falta tag "total"');
        if (!empty($this->retTrib)) {
            $this->dom->appChild($this->total, $this->retTrib, 'Falta tag "total"');
        }
        //[28a] tag total (326 W01)
        $this->dom->appChild($this->infNFe, $this->total, 'Falta tag "infNFe"');
        //mota a tag vol
        $this->buildVol();
        //[32] tag transp (356 X01)
        $this->dom->appChild($this->infNFe, $this->transp, 'Falta tag "infNFe"');
        //[39a] tag cobr (389 Y01)
        $this->dom->appChild($this->infNFe, $this->cobr, 'Falta tag "infNFe"');

        //[42] tag pag (398a YA01)
        //processa Pag e coloca as tags na tag pag
        $this->buildTagPag();

        //[43] tag infIntermed (398.26 YB01) NT 2020.006_1.00
        $this->dom->appChild($this->infNFe, $this->intermed, 'Falta tag "infNFe"');
        //[44] tag infAdic (399 Z01)
        $this->dom->appChild($this->infNFe, $this->infAdic, 'Falta tag "infNFe"');
        //[48] tag exporta (402 ZA01)
        $this->dom->appChild($this->infNFe, $this->exporta, 'Falta tag "infNFe"');
        //[49] tag compra (405 ZB01)
        $this->dom->appChild($this->infNFe, $this->compra, 'Falta tag "infNFe"');
        //[50] tag cana (409 ZC01)
        $this->dom->appChild($this->infNFe, $this->cana, 'Falta tag "infNFe"');
        //Responsável Técnico
        $this->dom->appChild($this->infNFe, $this->infRespTec, 'Falta tag "infNFe"');
        //[1] tag infNFe (1 A01)
        $this->dom->appChild($this->NFe, $this->infNFe, 'Falta tag "NFe"');
        //[0] tag NFe
        $this->dom->appendChild($this->NFe);
        // testa da chave
        $this->checkNFeKey($this->dom);
        $this->xml = $this->dom->saveXML();

        if (count($this->errors) > 0) {
            throw new \RuntimeException('Existem erros nas tags. Obtenha os erros com getErrors().');
        }

        return $this->xml;
    }

    /**
     * Informações de identificação da NF-e B01 pai A01
     * NOTA: Ajustado para NT2020_006_v1.00
     * tag NFe/infNFe/ide
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagide(\stdClass $std)
    {
        $possible = [
            'cUF',
            'cNF',
            'natOp',
            'indPag',
            'mod',
            'serie',
            'nNF',
            'dhEmi',
            'dhSaiEnt',
            'tpNF',
            'idDest',
            'cMunFG',
            'tpImp',
            'tpEmis',
            'cDV',
            'tpAmb',
            'finNFe',
            'indFinal',
            'indPres',
            'indIntermed',
            'procEmi',
            'verProc',
            'dhCont',
            'xJust'
        ];
        $std = $this->equilizeParameters($std, $possible);

        if (empty($std->cNF)) {
            $std->cNF = Keys::random($std->nNF);
        }
        if (empty($std->cDV)) {
            $std->cDV = 0;
        }
        //validação conforme NT 2019.001
        $std->cNF = str_pad($std->cNF, 8, '0', STR_PAD_LEFT);
        if (intval($std->cNF) == intval($std->nNF)) {
            throw new \InvalidArgumentException("O valor [{$std->cNF}] não é "
                . "aceitável para cNF, não pode ser igual ao de nNF, vide NT2019.001");
        }
        if (method_exists(Keys::class, 'cNFIsValid')) {
            if (!Keys::cNFIsValid($std->cNF)) {
                throw new \InvalidArgumentException("O valor [{$std->cNF}] para cNF "
                    . "é invalido, deve respeitar a NT2019.001");
            }
        }
        $this->tpAmb = $std->tpAmb;
        $this->mod = $std->mod;
        $identificador = 'B01 <ide> - ';
        $ide = $this->dom->createElement("ide");
        $this->dom->addChild(
            $ide,
            "cUF",
            $std->cUF,
            true,
            $identificador . "Código da UF do emitente do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "cNF",
            $std->cNF,
            true,
            $identificador . "Código Numérico que compõe a Chave de Acesso"
        );
        $this->dom->addChild(
            $ide,
            "natOp",
            substr(trim($std->natOp), 0, 60),
            true,
            $identificador . "Descrição da Natureza da Operação"
        );
        $this->dom->addChild(
            $ide,
            "mod",
            $std->mod,
            true,
            $identificador . "Código do Modelo do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "serie",
            $std->serie,
            true,
            $identificador . "Série do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "nNF",
            $std->nNF,
            true,
            $identificador . "Número do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "dhEmi",
            $std->dhEmi,
            true,
            $identificador . "Data e hora de emissão do Documento Fiscal"
        );
        if ($std->mod == '55' && !empty($std->dhSaiEnt)) {
            $this->dom->addChild(
                $ide,
                "dhSaiEnt",
                $std->dhSaiEnt,
                false,
                $identificador . "Data e hora de Saída ou da Entrada da Mercadoria/Produto"
            );
        }
        $this->dom->addChild(
            $ide,
            "tpNF",
            $std->tpNF,
            true,
            $identificador . "Tipo de Operação"
        );
        $this->dom->addChild(
            $ide,
            "idDest",
            $std->idDest,
            true,
            $identificador . "Identificador de local de destino da operação"
        );
        $this->dom->addChild(
            $ide,
            "cMunFG",
            $std->cMunFG,
            true,
            $identificador . "Código do Município de Ocorrência do Fato Gerador"
        );
        $this->dom->addChild(
            $ide,
            "tpImp",
            $std->tpImp,
            true,
            $identificador . "Formato de Impressão do DANFE"
        );
        $this->dom->addChild(
            $ide,
            "tpEmis",
            $std->tpEmis,
            true,
            $identificador . "Tipo de Emissão da NF-e"
        );
        $this->dom->addChild(
            $ide,
            "cDV",
            !empty($std->cDV) ? $std->cDV : '0',
            true,
            $identificador . "Dígito Verificador da Chave de Acesso da NF-e"
        );
        $this->dom->addChild(
            $ide,
            "tpAmb",
            $std->tpAmb,
            true,
            $identificador . "Identificação do Ambiente"
        );
        $this->dom->addChild(
            $ide,
            "finNFe",
            $std->finNFe,
            true,
            $identificador . "Finalidade de emissão da NF-e"
        );
        $this->dom->addChild(
            $ide,
            "indFinal",
            $std->indFinal,
            true,
            $identificador . "Indica operação com Consumidor final"
        );
        $this->dom->addChild(
            $ide,
            "indPres",
            $std->indPres,
            true,
            $identificador . "Indicador de presença do comprador no estabelecimento comercial no momento da operação"
        );
        $this->dom->addChild(
            $ide,
            "indIntermed",
            isset($std->indIntermed) ? $std->indIntermed : null,
            false,
            $identificador . "Indicador de intermediador/marketplace"
        );
        $this->dom->addChild(
            $ide,
            "procEmi",
            $std->procEmi,
            true,
            $identificador . "Processo de emissão da NF-e"
        );
        $this->dom->addChild(
            $ide,
            "verProc",
            $std->verProc,
            true,
            $identificador . "Versão do Processo de emissão da NF-e"
        );
        if (!empty($std->dhCont) && !empty($std->xJust)) {
            $this->dom->addChild(
                $ide,
                "dhCont",
                $std->dhCont,
                true,
                $identificador . "Data e Hora da entrada em contingência"
            );
            $this->dom->addChild(
                $ide,
                "xJust",
                substr(trim($std->xJust), 0, 256),
                true,
                $identificador . "Justificativa da entrada em contingência"
            );
        }
        $this->ide = $ide;
        return $ide;
    }

    /**
     * Identificação do emitente da NF-e C01 pai A01
     * tag NFe/infNFe/emit
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagemit(\stdClass $std)
    {
        $possible = [
            'xNome',
            'xFant',
            'IE',
            'IEST',
            'IM',
            'CNAE',
            'CRT',
            'CNPJ',
            'CPF'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $identificador = 'C01 <emit> - ';
        $this->emit = $this->dom->createElement("emit");
        if (!empty($std->CNPJ)) {
            $this->dom->addChild(
                $this->emit,
                "CNPJ",
                Strings::onlyNumbers($std->CNPJ),
                false,
                $identificador . "CNPJ do emitente"
            );
        } elseif (!empty($std->CPF)) {
            $this->dom->addChild(
                $this->emit,
                "CPF",
                Strings::onlyNumbers($std->CPF),
                false,
                $identificador . "CPF do remetente"
            );
        }
        $this->dom->addChild(
            $this->emit,
            "xNome",
            substr(trim($std->xNome), 0, 60),
            true,
            $identificador . "Razão Social ou Nome do emitente"
        );
        $this->dom->addChild(
            $this->emit,
            "xFant",
            substr(trim($std->xFant), 0, 60),
            false,
            $identificador . "Nome fantasia do emitente"
        );
        if ($std->IE != 'ISENTO') {
            $std->IE = Strings::onlyNumbers($std->IE);
        }
        $this->dom->addChild(
            $this->emit,
            "IE",
            $std->IE,
            true,
            $identificador . "Inscrição Estadual do emitente"
        );
        $this->dom->addChild(
            $this->emit,
            "IEST",
            Strings::onlyNumbers($std->IEST),
            false,
            $identificador . "IE do Substituto Tributário do emitente"
        );
        $this->dom->addChild(
            $this->emit,
            "IM",
            Strings::onlyNumbers($std->IM),
            false,
            $identificador . "Inscrição Municipal do Prestador de Serviço do emitente"
        );
        if (!empty($std->IM) && !empty($std->CNAE)) {
            $this->dom->addChild(
                $this->emit,
                "CNAE",
                Strings::onlyNumbers($std->CNAE),
                false,
                $identificador . "CNAE fiscal do emitente"
            );
        }
        $this->dom->addChild(
            $this->emit,
            "CRT",
            $std->CRT,
            true,
            $identificador . "Código de Regime Tributário do emitente"
        );
        return $this->emit;
    }

    /**
     * Endereço do emitente C05 pai C01
     * tag NFe/infNFe/emit/endEmit
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagenderEmit(\stdClass $std)
    {
        $possible = [
            'xLgr',
            'nro',
            'xCpl',
            'xBairro',
            'cMun',
            'xMun',
            'UF',
            'CEP',
            'cPais',
            'xPais',
            'fone'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = 'C05 <enderEmit> - ';
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->dom->addChild(
            $this->enderEmit,
            "xLgr",
            substr(trim($std->xLgr), 0, 60),
            true,
            $identificador . "Logradouro do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "nro",
            substr(trim($std->nro), 0, 60),
            true,
            $identificador . "Número do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xCpl",
            substr(trim($std->xCpl), 0, 60),
            false,
            $identificador . "Complemento do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xBairro",
            substr(trim($std->xBairro), 0, 60),
            true,
            $identificador . "Bairro do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "cMun",
            Strings::onlyNumbers($std->cMun),
            true,
            $identificador . "Código do município do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xMun",
            substr(trim($std->xMun), 0, 60),
            true,
            $identificador . "Nome do município do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "UF",
            strtoupper(trim($std->UF)),
            true,
            $identificador . "Sigla da UF do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "CEP",
            Strings::onlyNumbers($std->CEP),
            true,
            $identificador . "Código do CEP do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "cPais",
            Strings::onlyNumbers($std->cPais),
            false,
            $identificador . "Código do País do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xPais",
            substr(trim($std->xPais), 0, 60),
            false,
            $identificador . "Nome do País do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "fone",
            trim($std->fone),
            false,
            $identificador . "Telefone do Endereço do emitente"
        );
        $node = $this->emit->getElementsByTagName("IE")->item(0);
        $this->emit->insertBefore($this->enderEmit, $node);
        return $this->enderEmit;
    }

    /**
     * Informações suplementares da Nota Fiscal
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function taginfNFeSupl(\stdClass $std)
    {
        $possible = ['qrcode', 'urlChave'];
        $std = $this->equilizeParameters($std, $possible);

        $infNFeSupl = $this->dom->createElement("infNFeSupl");
        $nodeqr = $infNFeSupl->appendChild($this->dom->createElement('qrCode'));
        $nodeqr->appendChild($this->dom->createCDATASection($std->qrcode));
        //incluido no layout 4.00
        $std->urlChave = !empty($std->urlChave) ? $std->urlChave : null;
        $this->dom->addChild(
            $infNFeSupl,
            "urlChave",
            $std->urlChave,
            false,
            "URL de consulta por chave de acesso a ser impressa no DANFE NFC-e"
        );
        $this->infNFeSupl = $infNFeSupl;
        return $infNFeSupl;
    }

    /**
     * Tag raiz da NFe
     * tag NFe DOMNode
     * Função chamada pelo método [ monta ]
     *
     * @return \DOMElement
     */
    protected function buildNFe()
    {
        if (empty($this->NFe)) {
            $this->NFe = $this->dom->createElement("NFe");
            $this->NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
        }
        return $this->NFe;
    }

    /**
     * Insere dentro da tag det os produtos
     * tag NFe/infNFe/det[]
     * @return array|string
     */
    protected function buildDet()
    {
        if (empty($this->aProd)) {
            return '';
        }
        //insere NVE
        foreach ($this->aNVE as $nItem => $nve) {
            $prod = $this->aProd[$nItem];
            foreach ($nve as $child) {
                $node = $prod->getElementsByTagName("cBenef")->item(0);
                if (empty($node)) {
                    $node = $prod->getElementsByTagName("EXTIPI")->item(0);
                    if (empty($node)) {
                        $node = $prod->getElementsByTagName("CFOP")->item(0);
                    }
                }
                $prod->insertBefore($child, $node);
            }
        }
        //insere CEST
        foreach ($this->aCest as $nItem => $cest) {
            $prod = $this->aProd[$nItem];
            /** @var \DOMElement $child */
            foreach ($cest as $child) {
                $node = $prod->getElementsByTagName("cBenef")->item(0);
                if (empty($node)) {
                    $node = $prod->getElementsByTagName("EXTIPI")->item(0);
                    if (empty($node)) {
                        $node = $prod->getElementsByTagName("CFOP")->item(0);
                    }
                }
                $cchild = $child->getElementsByTagName("CEST")->item(0);
                $prod->insertBefore($cchild, $node);
                $cchild = $child->getElementsByTagName("indEscala")->item(0);
                if (!empty($cchild)) {
                    $prod->insertBefore($cchild, $node);
                }
                $cchild = $child->getElementsByTagName("CNPJFab")->item(0);
                if (!empty($cchild)) {
                    $prod->insertBefore($cchild, $node);
                    $this->aProd[$nItem] = $prod;
                }
            }
        }
        //insere DI
        foreach ($this->aDI as $nItem => $aDI) {
            $prod = $this->aProd[$nItem];
            foreach ($aDI as $child) {
                $nodexped = $prod->getElementsByTagName("xPed")->item(0);
                if (!empty($nodexped)) {
                    $prod->insertBefore($child, $nodexped);
                } else {
                    $nodenItemPed = $prod->getElementsByTagName("nItemPed")->item(0);
                    if (!empty($nodenItemPed)) {
                        $prod->insertBefore($child, $nodenItemPed);
                    } else {
                        $node = $prod->getElementsByTagName("FCI")->item(0);
                        if (!empty($node)) {
                            $prod->insertBefore($child, $node);
                        } else {
                            $this->dom->appChild($prod, $child, "Inclusão do node DI");
                        }
                    }
                }
            }
            $this->aProd[$nItem] = $prod;
        }
        //insere detExport
        foreach ($this->aDetExport as $nItem => $detexport) {
            $prod = $this->aProd[$nItem];
            foreach ($detexport as $child) {
                $node = $prod->getElementsByTagName("xPed")->item(0);
                if (!empty($node)) {
                    $prod->insertBefore($child, $node);
                } else {
                    $this->dom->appChild($prod, $child, "Inclusão do node DetExport");
                }
            }
            $this->aProd[$nItem] = $prod;
        }
        //insere Rastro
        foreach ($this->aRastro as $nItem => $child) {
            $prod = $this->aProd[$nItem];
            foreach ($child as $rastro) {
                $this->dom->appChild($prod, $rastro, "Inclusão do node Rastro");
            }
            $this->aProd[$nItem] = $prod;
        }
        //insere veiculo
        foreach ($this->aVeicProd as $nItem => $child) {
            $prod = $this->aProd[$nItem];
            $this->dom->appChild($prod, $child, "Inclusão do node veiculo");
            $this->aProd[$nItem] = $prod;
        }
        //insere medicamentos
        foreach ($this->aMed as $nItem => $child) {
            $prod = $this->aProd[$nItem];
            $this->dom->appChild($prod, $child, "Inclusão do node medicamento");
            $this->aProd[$nItem] = $prod;
        }
        //insere armas
        foreach ($this->aArma as $nItem => $arma) {
            $prod = $this->aProd[$nItem];
            foreach ($arma as $child) {
                $node = $prod->getElementsByTagName("imposto")->item(0);
                if (!empty($node)) {
                    $prod->insertBefore($child, $node);
                } else {
                    $this->dom->appChild($prod, $child, "Inclusão do node arma");
                }
            }
            $this->aProd[$nItem] = $prod;
        }
        //insere combustivel
        foreach ($this->aComb as $nItem => $child) {
            $prod = $this->aProd[$nItem];
            if (!empty($this->aEncerrante)) {
                $encerrante = $this->aEncerrante[$nItem];
                if (!empty($encerrante)) {
                    $this->dom->appChild($child, $encerrante, "inclusão do node encerrante na tag comb");
                }
            }
            $this->dom->appChild($prod, $child, "Inclusão do node combustivel");
            $this->aProd[$nItem] = $prod;
        }
        //insere RECOPI
        foreach ($this->aRECOPI as $nItem => $child) {
            $prod = $this->aProd[$nItem];
            $this->dom->appChild($prod, $child, "Inclusão do node RECOPI");
            $this->aProd[$nItem] = $prod;
        }
        //montagem da tag imposto[]
        $this->buildImp();
        //montagem da tag det[]
        foreach ($this->aProd as $nItem => $prod) {
            $det = $this->dom->createElement("det");
            $det->setAttribute("nItem", $nItem);
            $det->appendChild($prod);
            //insere imposto
            if (!empty($this->aImposto[$nItem])) {
                $child = $this->aImposto[$nItem];
                $this->dom->appChild($det, $child, "Inclusão do node imposto");
            }
            //insere impostoDevol
            if (!empty($this->aImpostoDevol[$nItem])) {
                $child = $this->aImpostoDevol[$nItem];
                $this->dom->appChild($det, $child, "Inclusão do node impostoDevol");
            }
            //insere infAdProd
            if (!empty($this->aInfAdProd[$nItem])) {
                $child = $this->aInfAdProd[$nItem];
                $this->dom->appChild($det, $child, "Inclusão do node infAdProd");
            }
            $this->aDet[] = $det;
            $det = null;
        }
        return $this->aProd;
    }

    /**
     * Retorna apenas o valor da tag informada
     * @param \DOMElement $node
     * @param string $name
     * @return string
     */
    private function getNodeValue(\DOMElement $node, string $name)
    {
        $dom = new Dom('1.0', 'utf-8');
        $dom->loadXML("<root></root>");
        $newnode = $dom->importNode($node, true);
        $dom->documentElement->appendChild($newnode);
        return $dom->getNodeValue($name);
    }

    /**
     * Remonta a chave da NFe de 44 digitos com base em seus dados
     * já contidos na NFE.
     * Isso é útil no caso da chave informada estar errada
     * se a chave estiver errada a mesma é substituida
     * @param Dom $dom
     * @return void
     */
    protected function checkNFeKey(Dom $dom)
    {
        $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
        /** @var \DOMElement $ide */
        $ide = $dom->getElementsByTagName("ide")->item(0);
        /** @var \DOMElement $emit */
        $emit = $dom->getElementsByTagName("emit")->item(0);
        $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $dhEmi = $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue;
        if (!empty($emit->getElementsByTagName('CNPJ')->item(0)->nodeValue)) {
            $doc = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        } else {
            $doc = $emit->getElementsByTagName('CPF')->item(0)->nodeValue;
        }
        $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue;
        /** @var \DOMElement $infNFe */
        $chave = str_replace('NFe', '', $infNFe->getAttribute("Id"));
        $dt = new \DateTime($dhEmi);
        /** @var \DOMElement $infRespTec */
        $infRespTec = $dom->getElementsByTagName("infRespTec")->item(0);
        $chaveMontada = Keys::build(
            $cUF,
            $dt->format('y'),
            $dt->format('m'),
            $doc,
            $mod,
            $serie,
            $nNF,
            $tpEmis,
            $cNF
        );
        if (empty($chave)) {
            //chave não foi passada por parâmetro então colocar a chavemontada
            $infNFe->setAttribute('Id', "NFe$chaveMontada");
            $chave = $chaveMontada;
            $this->chNFe = $chaveMontada;
            $ide->getElementsByTagName('cDV')->item(0)->nodeValue = substr($chave, -1);
            //trocar também o hash se o CSRT for passado
            if (!empty($this->csrt)) {
                $hashCSRT = $this->hashCSRT($this->csrt);
                $infRespTec->getElementsByTagName("hashCSRT")
                    ->item(0)->nodeValue = $hashCSRT;
            }
        }
        //caso a chave contida na NFe esteja errada
        //substituir a chave
        if ($chaveMontada != $chave) {
            $this->chNFe = $chaveMontada;
            $this->errors[] = "A chave informada está incorreta [$chave] => [correto: $chaveMontada].";
        }
    }

    /**
     * Includes missing or unsupported properties in std class
     * Replace all unsuported chars
     * @param \stdClass $std
     * @param array $possible
     * @return \stdClass
     */
    protected function equilizeParameters(\stdClass $std, $possible)
    {
        return Strings::equilizeParameters(
            $std,
            $possible,
            $this->replaceAccentedChars
        );
    }

    /**
     * Calcula hash sha1 retornando Base64Binary
     * @param string $CSRT
     * @return string
     */
    protected function hashCSRT($CSRT)
    {
        $comb = $CSRT . $this->chNFe;
        return base64_encode(sha1($comb, true));
    }

    /**
     * Formatação numerica condicional
     * @param string|float|int|null $value
     * @param int $decimal
     * @return string
     */
    protected function conditionalNumberFormatting($value = null, $decimal = 2)
    {
        if (is_numeric($value)) {
            return number_format($value, $decimal, '.', '');
        }
        return null;
    }
}

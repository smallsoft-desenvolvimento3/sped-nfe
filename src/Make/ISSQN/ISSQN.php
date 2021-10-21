<?php

/**
 * Trait Helper para impostos relacionados a ISSQN
 * Esta trait basica está estruturada para montar as tags de ISSQN para o
 * layout versão 4.00, os demais modelos serão derivados deste
 *
 * @category  API
 * @package   NFePHP\NFe\
 * @copyright Copyright (c) 2008-2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @author    Gustavo Lidani <lidanig0 at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

namespace NFePHP\NFe\Make\ISSQN;

trait ISSQN
{

    /**
     * @var array<\DOMElement>
     */
    protected $aISSQN = [];

    /**
     * Grupo ISSQN U01 pai M01
     * tag NFe/infNFe/det[]/imposto/ISSQN (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagISSQN(\stdClass $std)
    {
        $possible = [
            'item',
            'vBC',
            'vAliq',
            'vISSQN',
            'cMunFG',
            'cListServ',
            'vDeducao',
            'vOutro',
            'vDescIncond',
            'vDescCond',
            'vISSRet',
            'indISS',
            'cServico',
            'cMun',
            'cPais',
            'nProcesso',
            'indIncentivo'
        ];

        $std = $this->equilizeParameters($std, $possible);

        // Adiciona o totalizador, somente se maior que ZERO
        empty($std->vBC) ?: $this->stdISSQNTot->vBC += (float) $std->vBC;
        empty($std->vISSQN) ?: $this->stdISSQNTot->vISS += $std->vISSQN ?? 0.0;
        empty($std->vISSRet) ?: $this->stdISSQNTot->vISSRet += $std->vISSRet ?? 0.0;
        empty($std->vDeducao) ?: $this->stdISSQNTot->vDeducao += $std->vDeducao ?? 0.0;
        empty($std->vOutro) ?: $this->stdISSQNTot->vOutro += $std->vOutro ?? 0.0;
        empty($std->vDescIncond) ?: $this->stdISSQNTot->vDescIncond += $std->vDescIncond ?? 0.0;
        empty($std->vDescCond) ?: $this->stdISSQNTot->vDescCond += $std->vDescCond ?? 0.0;

        array_push($this->aItensServ, $std->item);

        // totalizador
        if ($this->aProd[$std->item]->getElementsByTagName('indTot')->item(0)->nodeValue == 1) {
            // Captura o valor do item
            $vProd = (float) ($this->aProd[$std->item]->getElementsByTagName('vProd')->item(0)->nodeValue);

            // Remove o valor to totalizador de produtos e Adiciona o valor do item no totalizador de serviços
            $this->stdTot->vProd -= $vProd;
            $this->stdISSQNTot->vServ += $vProd;
        }

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'U01 <ISSQN> -';

        $issqn = $this->dom->createElement('ISSQN');

        $this->dom->addChild(
            $issqn,
            "vBC",
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do ISSQN"
        );
        $this->dom->addChild(
            $issqn,
            "vAliq",
            $this->conditionalNumberFormatting($std->vAliq, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do ISSQN"
        );
        $this->dom->addChild(
            $issqn,
            "vISSQN",
            $this->conditionalNumberFormatting($std->vISSQN),
            true,
            "{$identificador} [item $std->item] Valor do ISSQN"
        );
        $this->dom->addChild(
            $issqn,
            "cMunFG",
            $std->cMunFG,
            true,
            "{$identificador} [item $std->item] Código do município de ocorrência do fato gerador do ISSQN"
        );
        $this->dom->addChild(
            $issqn,
            "cListServ",
            $std->cListServ,
            true,
            "{$identificador} [item $std->item] Item da Lista de Serviços"
        );
        $this->dom->addChild(
            $issqn,
            "vDeducao",
            $this->conditionalNumberFormatting($std->vDeducao),
            false,
            "{$identificador} [item $std->item] Valor dedução para redução da Base de Cálculo"
        );
        $this->dom->addChild(
            $issqn,
            "vOutro",
            $this->conditionalNumberFormatting($std->vOutro),
            false,
            "{$identificador} [item $std->item] Valor outras retenções"
        );
        $this->dom->addChild(
            $issqn,
            "vDescIncond",
            $this->conditionalNumberFormatting($std->vDescIncond),
            false,
            "{$identificador} [item $std->item] Valor desconto incondicionado"
        );
        $this->dom->addChild(
            $issqn,
            "vDescCond",
            $this->conditionalNumberFormatting($std->vDescCond),
            false,
            "{$identificador} [item $std->item] Valor desconto condicionado"
        );
        $this->dom->addChild(
            $issqn,
            "vISSRet",
            $this->conditionalNumberFormatting($std->vISSRet),
            false,
            "{$identificador} [item $std->item] Valor retenção ISS"
        );
        $this->dom->addChild(
            $issqn,
            "indISS",
            $std->indISS,
            true,
            "{$identificador} [item $std->item] Indicador da exigibilidade do ISS"
        );
        $this->dom->addChild(
            $issqn,
            "cServico",
            $std->cServico,
            false,
            "{$identificador} [item $std->item] Código do serviço prestado dentro do município"
        );
        $this->dom->addChild(
            $issqn,
            "cMun",
            $std->cMun,
            false,
            "{$identificador} [item $std->item] Código do Município de incidência do imposto"
        );
        $this->dom->addChild(
            $issqn,
            "cPais",
            $std->cPais,
            false,
            "{$identificador} [item $std->item] Código do País onde o serviço foi prestado"
        );
        $this->dom->addChild(
            $issqn,
            "nProcesso",
            $std->nProcesso,
            false,
            "{$identificador} [item $std->item] Número do processo judicial ou administrativo de suspensão da exigibilidade"
        );
        $this->dom->addChild(
            $issqn,
            "indIncentivo",
            $std->indIncentivo,
            true,
            "{$identificador} [item $std->item] Indicador de incentivo Fiscal"
        );

        $this->aISSQN[$std->item] = $issqn;

        return $issqn;
    }
}

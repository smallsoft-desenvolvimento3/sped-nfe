<?php

/**
 * Trait Helper para tags relacionados ao detalhamento de veículos novos
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

namespace NFePHP\NFe\Make\Det;

trait VeicProd
{
    /**
     * @var array<\DOMElement>
     */
    protected $aVeicProd = [];

    /**
     * Detalhamento de Veículos novos J01 pai I90
     * tag NFe/infNFe/det[]/prod/veicProd (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagveicProd(\stdClass $std)
    {
        $possible = [
            'item',
            'tpOp',
            'chassi',
            'cCor',
            'xCor',
            'pot',
            'cilin',
            'pesoL',
            'pesoB',
            'nSerie',
            'tpComb',
            'nMotor',
            'CMT',
            'dist',
            'anoMod',
            'anoFab',
            'tpPint',
            'tpVeic',
            'espVeic',
            'VIN',
            'condVeic',
            'cMod',
            'cCorDENATRAN',
            'lota',
            'tpRest'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "J01 <veicProd> - [item $std->item]:";

        $veicProd = $this->dom->createElement('veicProd');

        $this->dom->addChild(
            $veicProd,
            "tpOp",
            $std->tpOp,
            true,
            "$identificador Tipo da operação do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "chassi",
            $std->chassi,
            true,
            "$identificador Chassi do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "cCor",
            $std->cCor,
            true,
            "$identificador Cor do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "xCor",
            $std->xCor,
            true,
            "$identificador Descrição da Cor do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "pot",
            $std->pot,
            true,
            "$identificador Potência Motor (CV) do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "cilin",
            $std->cilin,
            true,
            "$identificador Cilindradas do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "pesoL",
            $this->conditionalNumberFormatting($std->pesoL, 3),
            true,
            "$identificador Peso Líquido do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "pesoB",
            $this->conditionalNumberFormatting($std->pesoB, 3),
            true,
            "$identificador Peso Bruto do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "nSerie",
            $std->nSerie,
            true,
            "$identificador Serial (série) do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "tpComb",
            $std->tpComb,
            true,
            "$identificador Tipo de combustível do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "nMotor",
            $std->nMotor,
            true,
            "$identificador Número de Motor do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "CMT",
            $this->conditionalNumberFormatting($std->CMT, 4),
            true,
            "$identificador Capacidade Máxima de Tração do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "dist",
            $std->dist,
            true,
            "$identificador Distância entre eixos do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "anoMod",
            $std->anoMod,
            true,
            "$identificador Ano Modelo de Fabricação do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "anoFab",
            $std->anoFab,
            true,
            "$identificador Ano de Fabricação do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "tpPint",
            $std->tpPint,
            true,
            "$identificador Tipo de Pintura do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "tpVeic",
            $std->tpVeic,
            true,
            "$identificador Tipo de Veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "espVeic",
            $std->espVeic,
            true,
            "$identificador Espécie de Veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "VIN",
            $std->VIN,
            true,
            "$identificador Condição do VIN do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "condVeic",
            $std->condVeic,
            true,
            "$identificador Condição do Veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "cMod",
            $std->cMod,
            true,
            "$identificador Código Marca Modelo do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "cCorDENATRAN",
            $std->cCorDENATRAN,
            true,
            "$identificador Código da Cor do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "lota",
            $std->lota,
            true,
            "$identificador Capacidade máxima de lotação do veículo"
        );
        $this->dom->addChild(
            $veicProd,
            "tpRest",
            $std->tpRest,
            true,
            "$identificador Restrição do veículo"
        );
        $this->aVeicProd[$std->item] = $veicProd;
        return $veicProd;
    }
}

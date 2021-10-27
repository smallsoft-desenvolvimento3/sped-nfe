<?php

/**
 * Trait Helper para impostos relacionados aos totais de Serviços
 * Esta trait basica está estruturada para montar a tag de total de ISSQN para o
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

namespace NFePHP\NFe\Make\Total;

trait ISSQNtot
{

    /**
     * @var \stdClass
     */
    protected $stdISSQN;

    /**
     * @var \DOMElement
     */
    protected $ISSQNTot;

    /**
     * @var array
     */
    protected $aItensServ = [];

    /**
     * @var bool
     */
    protected $flagISSQNCalc = false;

    /**
     * Grupo Totais referentes ao ISSQN W17 pai W01
     * tag NFe/infNFe/total/ISSQNTot (opcional)
     * @param \stdClass|null $std
     * @return \DOMElement|void
     */
    public function tagISSQNTot(\stdClass $std = null)
    {
        if (empty($this->aItensServ)) {
            // não existem itens com ISSQN
            return;
        }

        $this->buildISSQNtot();

        $possible = [
            'vServ',
            'vBC',
            'vISS',
            'vPIS',
            'vCOFINS',
            'dCompet',
            'vDeducao',
            'vOutro',
            'vDescIncond',
            'vDescCond',
            'vISSRet',
            'cRegTrib'
        ];
        if (isset($std)) {
            $std = $this->equilizeParameters($std, $possible);
        }

        $this->stdISSQN = $std;

        $vServ = isset($std->vServ) ? $std->vServ : $this->stdISSQNTot->vServ;
        $vBC = isset($std->vBC) ? $std->vBC : $this->stdISSQNTot->vBC;
        $vISS = isset($std->vISS) ? $std->vISS : $this->stdISSQNTot->vISS;
        $vPIS = isset($std->vPIS) ? $std->vPIS : $this->stdISSQNTot->vPIS;
        $vCOFINS = isset($std->vCOFINS) ? $std->vCOFINS : $this->stdISSQNTot->vCOFINS;
        $dCompet = isset($std->dCompet) ? $std->dCompet : date('Y-m-d');
        $vDeducao = isset($std->vDeducao) ? $std->vDeducao : $this->stdISSQNTot->vDeducao;
        $vOutro = isset($std->vOutro) ? $std->vOutro : $this->stdISSQNTot->vOutro;
        $vDescIncond = isset($std->vDescIncond) ? $std->vDescIncond : $this->stdISSQNTot->vDescIncond;
        $vDescCond = isset($std->vDescCond) ? $std->vDescCond : $this->stdISSQNTot->vDescCond;
        $vISSRet = isset($std->vISSRet) ? $std->vISSRet : $this->stdISSQNTot->vISSRet;
        $cRegTrib = isset($std->cRegTrib) ? $std->cRegTrib : $this->stdISSQNTot->cRegTrib;

        //nulificar caso seja menor ou igual a ZERO
        $vServ = ($vServ > 0) ? number_format($vServ, 2, '.', '') : null;
        $vBC = ($vBC > 0) ? number_format($vBC, 2, '.', '') : null;
        $vISS = ($vISS > 0) ? number_format($vISS, 2, '.', '') : null;
        $vPIS = ($vPIS > 0) ? number_format($vPIS, 2, '.', '') : null;
        $vCOFINS = ($vCOFINS > 0) ? number_format($vCOFINS, 2, '.', '') : null;
        $vDeducao = ($vDeducao > 0) ? number_format($vDeducao, 2, '.', '') : null;
        $vOutro = ($vOutro > 0) ? number_format($vOutro, 2, '.', '') : null;
        $vDescIncond = ($vDescIncond > 0) ? number_format($vDescIncond, 2, '.', '') : null;
        $vDescCond = ($vDescCond > 0) ? number_format($vDescCond, 2, '.', '') : null;
        $vISSRet = ($vISSRet > 0) ? number_format($vISSRet, 2, '.', '') : null;

        $ISSQNTot = $this->dom->createElement("ISSQNtot");
        $this->dom->addChild(
            $ISSQNTot,
            "vServ",
            $this->conditionalNumberFormatting($vServ),
            false,
            "Valor total dos Serviços sob não incidência ou não tributados pelo ICMS"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vBC",
            $this->conditionalNumberFormatting($vBC),
            false,
            "Valor total Base de Cálculo do ISS"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vISS",
            $this->conditionalNumberFormatting($vISS),
            false,
            "Valor total do ISS"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vPIS",
            $this->conditionalNumberFormatting($vPIS),
            false,
            "Valor total do PIS sobre serviços"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vCOFINS",
            $this->conditionalNumberFormatting($vCOFINS),
            false,
            "Valor total da COFINS sobre serviços"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "dCompet",
            $dCompet,
            true,
            "Data da prestação do serviço"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vDeducao",
            $this->conditionalNumberFormatting($vDeducao),
            false,
            "Valor total dedução para redução da Base de Cálculo"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vOutro",
            $this->conditionalNumberFormatting($vOutro),
            false,
            "Valor total outras retenções"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vDescIncond",
            $this->conditionalNumberFormatting($vDescIncond),
            false,
            "Valor total desconto incondicionado"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vDescCond",
            $this->conditionalNumberFormatting($vDescCond),
            false,
            "Valor total desconto condicionado"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "vISSRet",
            $this->conditionalNumberFormatting($vISSRet),
            false,
            "Valor total retenção ISS"
        );
        $this->dom->addChild(
            $ISSQNTot,
            "cRegTrib",
            $cRegTrib,
            false,
            "Código do Regime Especial de Tributação"
        );
        return $this->ISSQNTot = $ISSQNTot;
    }

    protected function buildISSQNtot()
    {
        if ($this->flagISSQNCalc) {
            return;
        }

        //totaliza PIS e COFINS dos Itens de Serviço
        foreach ($this->aItensServ as $item) {
            if (!empty($this->aPIS[$item])) {
                $vPIS = (float) $this->getNodeValue($this->aPIS[$item], 'vPIS');
                $this->stdISSQNTot->vPIS += (float) $vPIS;
                //remove esse valor do total já contabiizado no stdTot
                $this->stdTot->vPIS -= $vPIS;
            }
            //totalizar COFINS desses itens
            if (!empty($this->aCOFINS[$item])) {
                $vCOFINS = (float) $this->getNodeValue($this->aCOFINS[$item], 'vCOFINS');
                $this->stdISSQNTot->vCOFINS += (float) $vCOFINS;
                //remove esse valor do total já contabiizado no stdTot
                $this->stdTot->vCOFINS -= $vCOFINS;
            }
        }
        $this->stdISSQNTot->vServ = $this->conditionalNumberFormatting($this->stdISSQNTot->vServ);
        $this->stdISSQNTot->vBC = $this->conditionalNumberFormatting($this->stdISSQNTot->vBC);
        $this->stdISSQNTot->vISS = $this->conditionalNumberFormatting($this->stdISSQNTot->vISS);
        $this->stdISSQNTot->vPIS = $this->conditionalNumberFormatting($this->stdISSQNTot->vPIS);
        $this->stdISSQNTot->vCOFINS = $this->conditionalNumberFormatting($this->stdISSQNTot->vCOFINS);
        $this->stdISSQNTot->vDeducao = $this->conditionalNumberFormatting($this->stdISSQNTot->vDeducao);
        $this->stdISSQNTot->vOutro = $this->conditionalNumberFormatting($this->stdISSQNTot->vOutro);
        $this->stdISSQNTot->vDescIncond = $this->conditionalNumberFormatting($this->stdISSQNTot->vDescIncond);
        $this->stdISSQNTot->vDescCond = $this->conditionalNumberFormatting($this->stdISSQNTot->vDescCond);
        $this->stdISSQNTot->vISSRet = $this->conditionalNumberFormatting($this->stdISSQNTot->vISSRet);

        $this->flagISSQNCalc = true;
    }
}

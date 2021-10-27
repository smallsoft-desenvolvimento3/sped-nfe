<?php

/**
 * Trait Helper para tags relacionados ao vagão de transporte
 *
 * Essa trait depende da \NFePHP\NFe\Make\Transporte\Transp
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

namespace NFePHP\NFe\Make\Transporte;

trait Vagao
{
    /**
     * Campo Vagao X25a pai X01
     * tag NFe/infNFe/transp/vagao (opcional)
     * @param \stdClass $std
     */
    public function tagvagao(\stdClass $std)
    {
        $possible = [
            'vagao'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $this->dom->addChild(
            $this->transp,
            "vagao",
            $std->vagao,
            false,
            "Identificação do vagão do Veículo Reboque"
        );
    }
}

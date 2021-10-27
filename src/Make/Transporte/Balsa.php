<?php

/**
 * Trait Helper para tags relacionados à balsa de transporte
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

trait Balsa
{

    /**
     * Campo Balsa X25b pai X01
     * tag NFe/infNFe/transp/balsa (opcional)
     * @param \stdClass $std
     */
    public function tagbalsa(\stdClass $std)
    {
        $possible = [
            'balsa'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $this->dom->addChild(
            $this->transp,
            "balsa",
            $std->balsa,
            false,
            "Identificação da balsa do Veículo Reboque"
        );
    }
}

<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Views;
use Domain\AccountRequests\Entities\AccountRequest;
use Domain\AccountRequests\Metadata;

use Web\Block;
use Web\Template;

class InfoView extends Template
{
    public function __construct(AccountRequest $request)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'actions' => self::generateActionLinks($request)
        ];

        if ($this->outputFormat == 'html') {
            foreach ((array)$request as $k=>$v) {
                $vars[$k] = is_string($v) ? parent::escape($v) : $v;
            }
        }
        else {
            $vars['account_request'] = $request;
        }

        $this->blocks = [
            new Block('account_requests/info.inc', $vars)
        ];
    }

    public static function generateActionLinks($request): array
    {
        $actions = [];
        if (self::isViewable($request)) {
            $actions['self'] = [
                'href' => parent::generateUri('account_requests.view', ['id'=>$request->id])
            ];
        }
        if (self::isUpdatable($request)) {
            $actions['edit'] = [
                'href' => parent::generateUri('account_requests.update', ['id'=>$request->id])
            ];
        }
        if (self::isDeletable($request)) {
            $actions['delete'] = [
                'href' => parent::generateUri('account_requests.delete', ['id'=>$request->id])
            ];
        }
        if (self::isApplyable($request)) {
            $actions['apply'] = [
                'href' => parent::generateUri('account_requests.apply', ['id'=>$request->id])
            ];
        }
        return $actions;
    }

    private static function isViewable(AccountRequest $request): bool
    {
        return parent::isAllowed('account_requests', 'view');
    }

    private static function isUpdatable(AccountRequest $request): bool
    {
        return parent::isAllowed('account_requests', 'update')
            && $request->status != Metadata::STATUS_COMPLETED;
    }

    private static function isDeletable(AccountRequest $request): bool
    {
        return parent::isAllowed('account_requests', 'delete');
    }

    private static function isApplyable(AccountRequest $request): bool
    {
        return parent::isAllowed('account_requests', 'apply')
            && $request->status != Metadata::STATUS_COMPLETED;
    }
}

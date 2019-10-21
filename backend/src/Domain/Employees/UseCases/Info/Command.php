<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Employees\UseCases\Info;

use Domain\Employees\DataStorage\EmployeesRepository;

class Command
{
    private $repo;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $number): Response
    {
        try {
            return new Response($this->repo->load($number));
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}

<?

namespace Toolbox\Core\Repository;

interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param  array               $parameters
     * @param  RepositoryInterface $repository
     * @return array
     */
    public function apply(array $parameters, RepositoryInterface $repository);
}

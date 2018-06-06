<?

namespace Toolbox\Core\Repository;

interface RepositoryCriteriaInterface
{

    /**
     * Push Criteria for filter the query
     *
     * @param  CriteriaInterface $criteria
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria);


    /**
     * Resets Criteria
     *
     * @return $this
     */
    public function resetCriteria();

    /**
     * Get Collection of Criteria
     *
     * @return array
     */
    public function getCriteria();

    /**
     * Skip Criteria
     *
     * @param  bool  $status
     * @return $this
     */
    public function skipCriteria($status = true);
}

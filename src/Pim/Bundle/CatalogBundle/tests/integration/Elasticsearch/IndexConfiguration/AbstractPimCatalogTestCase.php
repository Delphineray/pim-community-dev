<?php

namespace Pim\Bundle\CatalogBundle\tests\integration\Elasticsearch\IndexConfiguration;

use Akeneo\Bundle\ElasticsearchBundle\Client;
use Akeneo\Test\Integration\Configuration;
use Akeneo\Test\Integration\TestCase;

/**
 * For each integration tests implemented in the subclass, this abstract:
 * - Resets the index (eg, removes the index configuration from ES and the documents indexed)
 * - It also provides function to make ES queries to that index and make sure the expected index are part of the result.
 *
 * @author    Samir Boulil <samir.boulil@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractPimCatalogTestCase extends TestCase
{
    const DOCUMENT_TYPE = 'pim_catalog_product';

    private const PAGE_SIZE = 100;

    /** @var Client */
    protected $esProductClient;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration([Configuration::getMinimalCatalogPath()]);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->esProductClient = $this->get('akeneo_elasticsearch.client.product');
        $this->addProducts();
    }

    /**
     * This method indexes dummy products in elastic search.
     */
    abstract protected function addProducts();

    /**
     * Indexes the given list of products
     *
     * @param array $products
     */
    protected function indexProductDocuments(array $products)
    {
        foreach ($products as $product) {
            $this->esProductClient->index(self::DOCUMENT_TYPE, $product['identifier'], $product);
        }

        $this->esProductClient->refreshIndex();
    }

    /**
     * Executes the given query and returns the list of skus found.
     *
     * @param array $query
     *
     * @return array
     */
    protected function getSearchQueryResults(array $query)
    {
        $identifiers = [];

        $query['size'] = self::PAGE_SIZE;
        $response = $this->esProductClient->search(self::DOCUMENT_TYPE, $query);

        foreach ($response['hits']['hits'] as $hit) {
            $identifiers[] = $hit['_source']['identifier'];
        }

        return $identifiers;
    }

    /**
     * Checks that the products found are effectively expected
     *
     * @param array $actualProductIdentifiers
     * @param array $expectedProductIdentifiers
     */
    protected function assertProducts(array $actualProductIdentifiers, array $expectedProductIdentifiers)
    {
        sort($actualProductIdentifiers);
        sort($expectedProductIdentifiers);

        $this->assertSame($expectedProductIdentifiers, $actualProductIdentifiers);
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductsStores Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 * @property \App\Model\Table\SubCategoriesTable|\Cake\ORM\Association\BelongsTo $SubCategories
 * @property \App\Model\Table\ThirdCategoriesTable|\Cake\ORM\Association\BelongsTo $ThirdCategories
 * @property \App\Model\Table\AislesTable|\Cake\ORM\Association\BelongsTo $Aisles
 * @property \App\Model\Table\ProductStatesTable|\Cake\ORM\Association\BelongsTo $ProductStates
 * @property \App\Model\Table\ProductStateMarkedBiesTable|\Cake\ORM\Association\BelongsTo $ProductStateMarkedBies
 *
 * @method \App\Model\Entity\ProductsStore get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductsStore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductsStore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductsStore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductsStore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductsStore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductsStore findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsStoresTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('products_stores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id'
        ]);
        $this->belongsTo('SubCategories', [
            'foreignKey' => 'sub_category_id'
        ]);
        $this->belongsTo('ThirdCategories', [
            'foreignKey' => 'third_category_id'
        ]);
        $this->belongsTo('Brands', [
            'foreignKey' => 'brand_id'
        ]);
        $this->belongsTo('Aisles', [
            'foreignKey' => 'aisle_id'
        ]);
        $this->belongsTo('ProductStates', [
            'foreignKey' => 'product_state_id'
        ]);

        $this->hasMany('PriceUpdates', [
            'foreignKey' => 'product_store_id',
        ]);

        $this->hasMany('CatalogUpdates', [
            'foreignKey' => 'product_store_id'
        ]);

        $this->hasMany('StockUpdates', [
            'foreignKey' => 'product_store_id'
        ]);

        $this->hasMany('DealUpdates', [
            'foreignKey' => 'product_store_id'
        ]);

        $this->hasMany('Detections', [
            'foreignKey' => 'product_store_id'
        ]);

        $this->hasOne('LastStock', [
            'className' => 'StockUpdates',
            'foreignKey' => false,
            'conditions' => function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $query) {
                $subquery = $query
                    ->connection()
                    ->newQuery()
                    ->select(['StockUpdates.id'])
                    ->from(['StockUpdates' => 'stock_updates'])
                    ->where(['ProductsStores.id = StockUpdates.product_store_id'])
                    ->order(['StockUpdates.stock_updated' => 'DESC'])
                    ->limit(1);

                return $exp->add(['LastStock.id' => $subquery]);
            }
        ]);

        $this->hasOne('LastCatalog', [
            'className' => 'CatalogUpdates',
            'foreignKey' => false,
            'conditions' => function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $query) {
                $subquery = $query
                    ->connection()
                    ->newQuery()
                    ->select(['CatalogUpdates.id'])
                    ->from(['CatalogUpdates' => 'catalog_updates'])
                    ->where(['ProductsStores.id = CatalogUpdates.product_store_id'])
                    ->order(['CatalogUpdates.catalog_date' => 'DESC'])
                    ->limit(1);

                return $exp->add(['LastCatalog.id' => $subquery]);
            }
        ]);

        $this->hasOne('LastPrice', [
            'className' => 'PriceUpdates',
            'foreignKey' => false,
            'conditions' => function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $query){
                $subquery = $query
                    ->connection()
                    ->newQuery()
                    ->select(['PriceUpdates.id'])
                    ->from(['PriceUpdates' => 'price_updates', 'Stores' => 'stores'])
                    ->where(['ProductsStores.id = PriceUpdates.product_store_id'])
                    ->order(['PriceUpdates.company_updated' => 'DESC'])
                    ->limit(1);

                return $exp->add(['LastPrice.id' => $subquery]);
            }
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('obs')
            ->allowEmpty('obs');

        $validator
            ->scalar('description')
            ->maxLength('description', 150)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('internal_code')
            ->maxLength('internal_code', 45)
            ->requirePresence('internal_code', 'create')
            ->notEmpty('internal_code');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['section_id'], 'Sections'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));
        $rules->add($rules->existsIn(['sub_category_id'], 'SubCategories'));
        $rules->add($rules->existsIn(['third_category_id'], 'ThirdCategories'));
        $rules->add($rules->existsIn(['aisle_id'], 'Aisles'));
        $rules->add($rules->existsIn(['product_state_id'], 'ProductStates'));

        return $rules;
    }
}

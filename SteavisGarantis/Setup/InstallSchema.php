<?php 
namespace Mageplaza\SteavisGarantis\Setup;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface{
    public function install(SchemaSetupInterface $setup,ModuleContextInterface $context){
        $setup->startSetup();
        $conn = $setup->getConnection();
        $tableName = $setup->getTable('sag_review');
        $tableName_api = $setup->getTable('sag_user');
        if($conn->isTableExists($tableName) != true){
            $table = $conn->newTable($tableName)
                            ->addColumn(
                                'id',
                                Table::TYPE_INTEGER,
                                null,
                     ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true]
                                )
								 ->addColumn(
                                'idSAG',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
                            ->addColumn(
                                'idProduct',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
                            ->addColumn(
                                'review_rating',
                                Table::TYPE_INTEGER,
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'review_text',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'reviewer_name',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'lastname',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'date_time',
                                Table::TYPE_DATETIME,
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'review_status',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'answer_text',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'answer_date_time',
                                Table::TYPE_DATETIME,
                                ['nullbale'=>false,'default'=>'']
                                )
							->addColumn(
                                'lang',
                                Table::TYPE_TEXT,
                                '2M',
                                ['nullbale'=>false,'default'=>'']
                                )
                            ->setOption('charset','utf8');
            $conn->createTable($table);
        }
		if($conn->isTableExists($tableName_api) != true){
            $table = $conn->newTable($tableName_api)
                            ->addColumn(
                                'id',
                                Table::TYPE_INTEGER,
                                null,
                     ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true]
                                )
								->addColumn(
                                'email',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								->addColumn(
                                'password',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								->addColumn(
                                'siteurl',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								->addColumn(
                                'apikey',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								->addColumn(
                                'lang',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								->addColumn(
                                'terms',
                                Table::TYPE_INTEGER,
                                ['nullable'=>false,'default'=>'']
                                )
								
								
                            ->setOption('charset','utf8');
            $conn->createTable($table);
        }
        $setup->endSetup();
    }
}
 ?>
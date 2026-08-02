# ជំពូកទី ៣៖ ការរចនាមូលដ្ឋានទិន្នន័យ (Chapter III: Database Design)

### ៣.១ ការវិភាគទិន្នន័យ (Data Analyzing)
នៅក្នុងដំណាក់កាលនេះ យើងបានសិក្សា និងវិភាគលើទិន្នន័យសំខាន់ៗដែលចាំបាច់សម្រាប់ដំណើរការប្រព័ន្ធ General Online Store។ ទិន្នន័យទាំងនោះរួមមាន៖
*   **ទិន្នន័យអ្នកប្រើប្រាស់ (User Data):** រួមមានឈ្មោះ អ៊ីមែល លេខសម្ងាត់ និងតួនាទី (Admin ឬ Customer)។
*   **ទិន្នន័យផលិតផល (Product Data):** រួមមានឈ្មោះផលិតផល ការពិពណ៌នា តម្លៃ ចំនួនក្នុងស្តុក និងរូបភាព។
*   **ទិន្នន័យប្រភេទផលិតផល (Category Data):** ប្រើសម្រាប់បែងចែកផលិតផលទៅតាមក្រុមនីមួយៗ។
*   **ទិន្នន័យការបញ្ជាទិញ (Order Data):** រួមមានព័ត៌មានអតិថិជន កាលបរិច្ឆេទ តម្លៃសរុប និងស្ថានភាពនៃការបញ្ជាទិញ។

### ៣.២ គំនូសបំព្រួញលំហូរទិន្នន័យ (Data Flow Diagram - DFD)
DFD បង្ហាញពីលំហូរនៃទិន្នន័យនៅក្នុងប្រព័ន្ធ ចាប់ពីការបញ្ចូលរហូតដល់ការបញ្ចេញលទ្ធផល។

#### ១. DFD Level 0 (Context Diagram)
បង្ហាញពីទំនាក់ទំនងរវាងប្រព័ន្ធទាំងមូលជាមួយតួអង្គខាងក្រៅ (Admin និង Customer)។
*   **Customer:** ស្វែងរកផលិតផល, បញ្ជាទិញ, មើលប្រវត្តិបញ្ជាទិញ។
*   **Admin:** គ្រប់គ្រងផលិតផល, គ្រប់គ្រងប្រភេទផលិតផល, ពិនិត្យមើលការបញ្ជាទិញ និងគ្រប់គ្រងអ្នកប្រើប្រាស់។

#### ២. DFD Level 1
បង្ហាញពីដំណើរការលម្អិតនៅក្នុងប្រព័ន្ធដូចជា៖
*   ដំណើរការចុះឈ្មោះ និងចូលប្រើប្រាស់ (Login Process)
*   ដំណើរការគ្រប់គ្រងទំនិញ (Product Management)
*   ដំណើរការបញ្ជាទិញ (Ordering Process)

### ៣.៣ គំនូសបំព្រួញទំនាក់ទំនងអង្គភាព (Entity Relationship Diagram - ERD)
ERD បង្ហាញពីទំនាក់ទំនងរវាងតារាងនីមួយៗនៅក្នុងមូលដ្ឋានទិន្នន័យ៖
*   **Users** មានទំនាក់ទំនងជាមួយ **Orders** (One-to-Many): អ្នកប្រើប្រាស់ម្នាក់អាចមានការបញ្ជាទិញច្រើន។
*   **Categories** មានទំនាក់ទំនងជាមួយ **Products** (One-to-Many): ប្រភេទផលិតផលមួយអាចមានផលិតផលច្រើន។
*   **Orders** មានទំនាក់ទំនងជាមួយ **Order_Items** (One-to-Many): ការបញ្ជាទិញមួយអាចមានទំនិញច្រើនមុខ។
*   **Products** មានទំនាក់ទំនងជាមួយ **Order_Items** (One-to-Many): ផលិតផលមួយអាចស្ថិតនៅក្នុងការបញ្ជាទិញច្រើន។

### ៣.៤ វចនានុក្រមទិន្នន័យ (Data Dictionary)

#### ១. តារាងអ្នកប្រើប្រាស់ (Table: users)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | INT | NO | PRI | NULL | auto_increment |
| name | VARCHAR(100) | NO | | NULL | |
| email | VARCHAR(100) | NO | UNI | NULL | |
| password | VARCHAR(255) | NO | | NULL | |
| role | ENUM('admin', 'customer') | YES | | 'customer' | |
| created_at | TIMESTAMP | YES | | CURRENT_TIMESTAMP | |

#### ២. តារាងប្រភេទផលិតផល (Table: categories)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | INT | NO | PRI | NULL | auto_increment |
| name | VARCHAR(100) | NO | | NULL | |
| slug | VARCHAR(100) | NO | UNI | NULL | |
| icon | VARCHAR(50) | YES | | NULL | |
| sort_order | INT | YES | | 0 | |
| created_at | TIMESTAMP | YES | | CURRENT_TIMESTAMP | |

#### ៣. តារាងផលិតផល (Table: products)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | INT | NO | PRI | NULL | auto_increment |
| name | VARCHAR(255) | NO | | NULL | |
| description | TEXT | YES | | NULL | |
| price | DECIMAL(10,2) | NO | | NULL | |
| compare_price | DECIMAL(10,2) | YES | | NULL | |
| stock | INT | NO | | 0 | |
| category_id | INT | YES | MUL | NULL | |
| image | VARCHAR(255) | YES | | NULL | |
| created_at | TIMESTAMP | YES | | CURRENT_TIMESTAMP | |

#### ៤. តារាងការបញ្ជាទិញ (Table: orders)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | INT | NO | PRI | NULL | auto_increment |
| user_id | INT | NO | MUL | NULL | |
| total_price | DECIMAL(10,2) | NO | | NULL | |
| status | ENUM('pending', 'completed', 'cancelled') | YES | | 'completed' | |
| invoice_number | VARCHAR(50) | NO | UNI | NULL | |
| created_at | TIMESTAMP | YES | | CURRENT_TIMESTAMP | |

#### ៥. តារាងទំនិញក្នុងការបញ្ជាទិញ (Table: order_items)
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | INT | NO | PRI | NULL | auto_increment |
| order_id | INT | NO | MUL | NULL | |
| product_id | INT | NO | MUL | NULL | |
| price | DECIMAL(10,2) | NO | | NULL | |
| quantity | INT | NO | | NULL | |

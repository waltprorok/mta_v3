<?php

use App\Blog;
use Illuminate\Database\Seeder;

class BlogTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Blog::insert([
            [
                'id' => '1',
                'author_id' => '1',
                'title' => 'The Coding Process',
                'slug' => 'mta-coding-process',
                'body' => 'MTA takes great pride the code that runs the web application. Each feature is thought out and tested before going to our experienced developer. 
                
Once the developer has coded and tested the new feature we then put it in the had of our testing team. There the team goes over how the new feature works before we release it.',
                'image' => 'MTA_20200221_120217.jpeg',
                'released_on' => '2020-01-07 09:00:00',
                'created_at' => '2019-07-01 09:00:00',
                'updated_at' => '2019-07-01 09:00:00',
            ],
            [
                'id' => '2',
                'author_id' => '1',
                'title' => 'MTA Loves Laravel',
                'slug' => 'mta-loves-laravel',
                'body' => 'MTA love the magic that Laravel provides right out of the box. Modern toolkit. Pinch of magic. 
                
An amazing ORM, painless routing, powerful queue library, and simple authentication give you the tools you need for modern, maintainable PHP. We sweat the small stuff to help you deliver amazing applications.',
                'image' => 'MTA_20200221_120201.jpg',
                'released_on' => '2019-12-02 09:00:00',
                'created_at' => '2019-08-01 09:00:00',
                'updated_at' => '2019-08-01 09:00:00',
            ],
            [
                'id' => '3',
                'author_id' => '1',
                'title' => 'MTA is getting built!',
                'slug' => 'mta-getting-built',
                'body' => 'We are super excited to bring you a great web application that will aid any music teacher in building a successful teaching business.

We are dedicated to delivering a one of a kind experience that enables you to advance as a teacher and business owner. MTA is dedicated to bringing quality software, years of teaching experience and peace of mind that will resonate with your students.

---

#### What we do

Have you every used excel spreadsheets, a notebook or just keep all your students in your head? It can be a real nightmare with scheduling, rescheduling, payments, and just plain communication with students and parents.

With MusicTeachersAid we aid in all of those areas. Once the initial students and basic set up is complete MTA goes to work with text/email reminders, payment process, automatic payments each month, and reporting.

We are currently building out the product and will be available in the near future.',
                'image' => 'MTA_20200221_120252.jpeg',
                'released_on' => '2020-02-01 09:00:00',
                'created_at' => '2019-09-01 09:00:00',
                'updated_at' => '2019-09-01 09:00:00',
            ],
        ]);
    }
}

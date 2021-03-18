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
                'title' => 'MTA Loves Laravel',
                'slug' => 'mta-loves-laravel',
                'body' => 'MTA love the magic that Laravel provides right out of the box. Modern toolkit. Pinch of magic. 
                
An amazing ORM, painless routing, powerful queue library, and simple authentication give you the tools you need for modern, maintainable PHP. We sweat the small stuff to help you deliver amazing applications.

For the security and basic peace of mind we went with an Amazing Framework that keeps getting better with every release.',
                'image' => 'MTA_20200221_120201.jpg',
                'released_on' => '2020-12-02 09:00:00',
                'created_at' => '2020-12-02 09:00:00',
                'updated_at' => '2020-12-02 09:00:00',
            ],
            [
                'id' => '2',
                'author_id' => '1',
                'title' => 'The Coding Process',
                'slug' => 'mta-coding-process',
                'body' => 'MTA takes great pride the code that runs the web application. Each feature is thought out and tested before going to our experienced developer. 
                
Once the developer has coded and tested the new feature we then put it in the had of our testing team. There the team goes over how the new feature works before we release it.

We are always open to new ideas but put a lot of time and effort into refining are special sauce.  Making the experience as great as it can be for you and your users.',
                'image' => 'MTA_20200221_120217.jpeg',
                'released_on' => '2021-01-06 09:00:00',
                'created_at' => '2021-01-06 09:00:00',
                'updated_at' => '2021-01-06 09:00:00',
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
                'image' => 'MTA_20210317_110314.jpeg',
                'released_on' => '2021-02-03 09:00:00',
                'created_at' => '2021-02-03 09:00:00',
                'updated_at' => '2021-02-03 09:00:00',
            ],
            [
                'id' => '4',
                'author_id' => '1',
                'title' => 'Scheduling Students',
                'slug' => 'scheduling-students',
                'body' => 'Having a great calendar for scheduling your students is very important. Most students are repeatedly going to keep coming back each week. We want to make this experience the best it can be.
                
Scheduling a one time lesson or a whole year\'s worth is made easy.  Whether the lesson is 15 minutes or an hour we have you covered.  Color code the lesson to help certain students stand out on your calendar.

---

##### Smart Scheduling Calendar

We have come up with a smart calendar that will automatically notify the next student on your make-up list that it is open to a make up lesson that is not within their normal scheduled time.  The student is then able to accept or decline.  If the student declines the next one on the list is notified until the open lesson is filled.

Let the application do the boring mundane tasks that we as teachers don\'t really like to do but is part of running a successful business.  With automation we can cut out hours spent calling or emailing students trying to fit their schedule with your schedule and it all becomes a difficult puzzle.
                ',
                'image' => 'MTA_20200527_060533.jpg',
                'released_on' => '2021-03-17 09:00:00',
                'created_at' => '2021-03-17 09:00:00',
                'updated_at' => '2021-03-17 09:00:00',
            ],
        ]);
    }
}

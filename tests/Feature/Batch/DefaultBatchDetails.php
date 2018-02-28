<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/21/2018
 * Time: 6:52 PM
 */

namespace Tests\Feature\Batch;


use Carbon\Carbon;

class DefaultBatchDetails
{
    public static function  today_date(){
        return Carbon::parse("this thursday")->format("Y-m-d");
    }
    public static function  session1(){
        return Carbon::parse("this thursday")->format("Y-m-d");
    }
    public static function  session2(){
        return Carbon::parse("this thursday")->modify("this saturday")->format("Y-m-d");
    }
    public static function  session3(){
        return Carbon::today()->modify("this saturday")->modify("this thursday")->format("Y-m-d");
    }


    public static function getBatch()
    {
        return [
            "course_id" => 1,
            "course_plan_id" => "1112",
            "start_date" => static::session1(),
            "duration" => "120",
            "status" => "pending",
            "mode_of_training" => "offline",
            "batch_urgency" => "seats_filling_fast",
            "location" => ["country" => "india", "city" => "bangalore"],
            "mentor" => [],
            "days" => [
                [
                    "day" => "thursday",
                    "time" => "12:00"
                ],
                [
                    "day" => "saturday",
                    "time" => "12:00"
                ]
            ],
            "batch_reference_name" => "",
            "reference_sem_name" => "",
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                    "_id" => getUuid()

                ],
                [
                    "module_name" => "Map reduce2",
                    "_id" => getUuid()
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "module_id" => getUuid()
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "module_id" => getUuid()
                ]
            ]

        ];
    }

    public static function getBatchStructure()
    {
        return [
            "batch_reference_name" => null,
            "batch_urgency" => "seats_filling_fast",
            "course_id" => 1,
            "course_plan_id" => "1112",
            "modules" => [
                [
                    "module_name" => "Map reduce1",

                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "date" => static::session1(),
                    "time" => "12:00"
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "date" => static::session2(),
                    "time" => "12:00"
                ]
            ],
            "days" => [[
                "day" => "thursday", "time" => "12:00"], [
                "day" => "saturday", "time" => "12:00"]],
            "duration" => "120",
            "location" => [
                "city" => "bangalore", "country" => "india"],
            "start_date" => static::today_date(),
            "status" => "pending",
        ];
    }

    public static function getUpdateDetails()
    {
        return [
            "start_date" => static::today_date(),
            "course_id" => 1,
            "batch_urgency" => "seats_filling_fast",
            "mentor" => [],
            "status" => "yet_to_start",
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "mode_of_training" => "online",
            "course_plan_id"  => '1122',
            "modules" => [
                [
                    "module_name" => "Map reduce1",

                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "date" => static::session1(),
                    "time" => "12:00"
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "topics" => ["what is map reduce"],
                    "materials" => [],
                    "assignments" => [],
                    "projects" => [],
                    "reference_materials" => [],
                    "mentors" => [],
                    "meeting" => [],
                    "students" => [],
                    "cancellation" => [],
                    "date" => static::session2(),
                    "time" => "12:00"
                ]
            ]
        ];
    }

    public static function getBatchUpdated()
    {

        return [
            "start_date" => static::today_date(),
            "course_id" => 1,
            "batch_urgency" => "seats_filling_fast",
            "mentor" => [],
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "date" => static::session1(),
                    "status" => "pending",
                    "time" => "12:00"
                ],
                [
                    "date" => static::session2(),
                    "status" => "pending",
                    "time" => "12:00"
                ]
            ],
        ];
    }

    public static function getCoursePlanUpdated()
    {

        return [
            "start_date" => static::today_date(),
            "course_id" => 1,
            "batch_urgency" => "seats_filling_fast",
            "mentor" => [],
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "course_plan_id" => "1124"
        ];
    }

    public static function getStartDateChange()
    {
        return [
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "date"  =>static::session1()
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "date"  =>static::session2()
                ]
            ],
        ];
    }

    public static function extraSessionDetails()
    {
        return [

                "session_heading" => "hadoop extra session",
                "session_topics" =>["sdfs","dsfd"],
                "requested_by"=> "525a6982-28f3-4892-ac30-445454545",

        ];
    }

    public static function extraSessionUpdated()
    {
        return [
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "date"  =>static::session1()
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "date"  =>static::session2()
                ],
                [
                    "heading" => "hadoop extra session",
                    "topic" => ["sdfs","dsfd"],
                    "date"  =>static::session3()
                ]
            ]

        ];
    }

    public static function extraSessionUpdatedAfterSession()
    {
        return[
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "date"  =>static::session1()
                ],

                [
                    "heading" => "Introduction to MapReduce2",
                    "date"  =>static::session3()
                 ],
                [
                    "heading" => "hadoop extra session",
                    "topic" => ["sdfs","dsfd"],
                    "date"  =>static::session2()
                ],
            ]
        ];
    }

    public static function extraSessionUpdatedWithDate()
    {
        return[
            "modules" => [
                [
                    "module_name" => "Map reduce1",
                ],
                [
                    "module_name" => "Map reduce2",
                ]
            ],
            "sessions" => [
                [
                    "heading" => "Introduction to MapReduce1",
                    "date"  =>static::session1()
                ],
                [
                    "heading" => "Introduction to MapReduce2",
                    "date"  =>static::session2()
                ],
                [
                    "heading" => "hadoop extra session",
                    "topic" => ["sdfs","dsfd"],
                    "date"  => "2018-03-30",
                    "time"  => "12:00"
                ],

            ]
        ];
    }

}
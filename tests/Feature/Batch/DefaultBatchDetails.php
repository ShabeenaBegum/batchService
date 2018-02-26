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
        return Carbon::today()->modify("this thursday")->format("Y-m-d");
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
            "mentor" => "",
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
            "course_session_details" => [
                "modules" => [
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "topics" => "what is map reduce",
                                "materials" => "",
                                "assignments" => "",
                                "projects" => "",
                                "reference_materials" => "",
                                "mentors" => "",
                                "meeting" => "",
                                "students" => "",
                                "cancellation" => ""
                            ]
                        ]
                    ],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "topics" => "what is map reduce",
                                "materials" => "",
                                "assignments" => "",
                                "projects" => "",
                                "reference_materials" => "",
                                "mentors" => "",
                                "meeting" => "",
                                "students" => "",
                                "cancellation" => ""
                            ]
                        ]
                    ]
                ]

            ],
        ];
    }

    public static function getBatchStructure()
    {
        return [
            "batch_reference_name" => null,
            "batch_urgency" => "seats_filling_fast",
            "course_id" => 1,
            "course_plan_id" => "1112",
            "course_session_details" => [
                "modules" => [[
                    "module_name" => "Map reduce",
                    "session_list" => [[
                        "assignments" => null,
                        "cancellation" => null,
                        "date" => static::session1(),
                        "heading" => "Introduction to MapReduce",
                        "materials" => null,
                        "meeting" => "",
                        "mentors" => null, "projects" => null,
                        "reference_materials" => null, "status" => "pending",
                        "students" => null,
                        "time" => "12:00",
                        "topics" => "what is map reduce"]]],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [[
                            "assignments" => null,
                            "cancellation" => null,
                            "date" => static::session2(),
                            "heading" => "Introduction to MapReduce",
                            "materials" => null, "meeting" => "",
                            "mentor" => null, "mentors" => null,
                            "projects" => null, "reference_materials" => null,
                            "status" => "pending", "students" => null,
                            "time" => "12:00",
                            "topics" => "what is map reduce"]]]]],
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
            "mentor" => "",
            "status" => "yet_to_start",
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "mode_of_training" => "online",
            "course_plan_id"  => '1122',
            "course_session_details" => [
                "modules" => [[
                    "module_name" => "Map reduce",
                    "session_list" => [[
                        "assignments" => null,
                        "cancellation" => null,
                        "date" => static::session1(),
                        "heading" => "Introduction to MapReduce",
                        "materials" => null,
                        "meeting" => "",
                        "mentors" => null, "projects" => null,
                        "reference_materials" => null, "status" => "pending",
                        "students" => null,
                        "time" => "12:00",
                        "topics" => "what is map reduce"]]],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [[
                            "assignments" => null,
                            "cancellation" => null,
                            "date" => static::session2(),
                            "heading" => "Introduction to MapReduce",
                            "materials" => null, "meeting" => "",
                            "mentor" => null, "mentors" => null,
                            "projects" => null, "reference_materials" => null,
                            "status" => "pending", "students" => null,
                            "time" => "12:00",
                            "topics" => "what is map reduce"]]]]],
        ];
    }

    public static function getBatchUpdated()
    {

        return [
            "start_date" => static::today_date(),
            "course_id" => 1,
            "batch_urgency" => "seats_filling_fast",
            "mentor" => "",
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "course_session_details" => [
                "modules" => [
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "date" => static::session1(),
                                "status" => "pending",
                                "time" => "12:00"
                            ],

                        ]
                    ],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [[
                            "date" => static::session2(),
                            "status" => "pending",
                            "time" => "12:00"]]
                    ]]
            ]
        ];
    }

    public static function getCoursePlanUpdated()
    {

        return [
            "start_date" => static::today_date(),
            "course_id" => 1,
            "batch_urgency" => "seats_filling_fast",
            "mentor" => "",
            "location" => ["country"=>"india"],
            "mock_interview" => 1,
            "course_plan_id" => "1124"
        ];
    }

    public static function getStartDateChange()
    {
        return [
            "course_session_details" => [
                "modules" => [
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "date"  =>static::session1()

                            ]
                        ]
                    ],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "date"  =>static::session2()

                            ]
                        ]
                    ]
                ]

            ]
        ];
    }

    public static function extraSessionDetails()
    {
        return [

                "session_heading" => "hadoop extra session",
                "session_topics" =>["sdfs","dsfd"],
//                "after_session_id" => "81fa3ceb-c8d1-4638-b999-a1afaf3d60e1",
                "requested_by"=> "525a6982-28f3-4892-ac30-445454545",
                "module_id" => "525a6982-28f3-4892-ac30-123abd6e8f99"

        ];
    }

    public static function extraSessionUpdated()
    {
        return [
            "course_session_details" => [
                "modules" => [
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "topics" => "what is map reduce",
                                "materials" => "",
                                "assignments" => "",
                                "projects" => "",
                                "reference_materials" => "",
                                "mentors" => "",
                                "meeting" => "",
                                "students" => "",
                                "cancellation" => "",
                                "time" => "12:00",
                                "date" => static::session1()
                            ],
                            [
                                "heading" => "hadoop extra session",
                                "topics" => ["sdfs","dsfd"],
                                "requested_by" => "525a6982-28f3-4892-ac30-445454545",
                                "session_date" => "",
                                "time" => "12:00",
                                "date" => static::session1()
                            ]

                        ]
                    ],
                    [
                        "module_name" => "Map reduce",
                        "session_list" => [
                            [
                                "heading" => "Introduction to MapReduce",
                                "topics" => "what is map reduce",
                                "materials" => "",
                                "assignments" => "",
                                "projects" => "",
                                "reference_materials" => "",
                                "mentors" => "",
                                "meeting" => "",
                                "students" => "",
                                "cancellation" => "",
                                "time" => "12:00",
                                "date" => static::session2()
                            ]
                        ]
                    ]
                ]

            ]
        ];
    }
}
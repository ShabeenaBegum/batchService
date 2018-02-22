<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/21/2018
 * Time: 6:52 PM
 */

namespace App\Batch\Services;


class DefaultBatchDetails
{
    public static function getBatch()
    {
        return [
            "course_id" => 1,
            "course_plan_id" => "1112",
            "start_date" => "2018-02-25",
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
                    "day" => "friday",
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
            "batch_name" => "2018-02-25",
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
                        "date" => "2018-02-25",
                        "heading" => "Introduction to MapReduce",
                        "materials" => null,
                        "meeting" => "", "mentor" => null,
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
                            "date" => "2018-03-02",
                            "heading" => "Introduction to MapReduce",
                            "materials" => null, "meeting" => "",
                            "mentor" => null, "mentors" => null,
                            "projects" => null, "reference_materials" => null,
                            "status" => "pending", "students" => null,
                            "time" => "12:00",
                            "topics" => "what is map reduce"]]]]],
            "days" => [[
                "day" => "thursday", "time" => "12:00"], [
                "day" => "friday", "time" => "12:00"]],
            "duration" => "120",
            "location" => [
                "city" => "bangalore", "country" => "india"],
            "start_date" => "2018-02-25",
            "status" => "pending",
        ];
    }
}
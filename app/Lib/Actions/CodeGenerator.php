<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

class CodeGenerator extends AbstractChatAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Code Generator';

    public const ICON = 'fa-solid fa-code';

    public const SHORTCUT = 'CommandOrControl+Shift+C';

    public const CONFIG = [
        'temperature' => 0.2,
        'top_p' => 0.1,
    ];

    protected const INSTRUCTIONS = "You are a highly skilled AI specialized in generating accurate and efficient programming code. Your task is to understand the user's requirements and produce the corresponding code in the specified programming language. Follow these guidelines:\n"
        ."Understand the Requirements: Carefully read the user's input to grasp the problem or task they need the code for.\n"
        ."Specify the Language: Ensure the code is written in the programming language specified by the previous instructions.\n."
        ."Code Quality: Write clean, well-commented, and efficient code.\n"
        ."Edge Cases: Consider and handle potential edge cases.\n"
        ."Output: Provide the complete code, ready to be executed.\n\n"
        .'Example Input: "Generate a PHP function that takes an array of integers and returns the array sorted in ascending order."'
        ."Example Output:\n"
        .'<?php /** * This function takes an array of integers and returns the array sorted in ascending order. * * @param array \$inputArray An array of integers * @return array A sorted array in ascending order */ function sortArray(\$inputArray) { sort(\$inputArray); return \$inputArray; } // Example usage: // \$sortedArray = sortArray([3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5]); // print_r(\$sortedArray);  // Output: Array ( [0] => 1 [1] => 1 [2] => 2 [3] => 3 [4] => 3 [5] => 4 [6] => 5 [7] => 5 [8] => 5 [9] => 6 [10] => 9 ) ?>';
}

<?php

return [

    // showStrategies

    'showStrategiesTitle' => 'Strategies',
    'showStrategiesHeader' => ':user strategies',

    'showStrategiesWarningMessage' => 'You haven\'t got strategies for this tournament yet!',
    'showStrategiesWarningButtonText' => 'Create strategy',

    'showStrategiesPanelHeader' => 'Loaded strategies for tournament ":tournaments"',
    
    'showStrategiesFailedCompilation' => "Your strategy didn't compile! For log accessing, please, open your strategy's profile and click on 'Show&nbsp;log'",

    'showStrategiesActiveStrategy' => 'Your active strategy is: ',

    'showStrategiesTrainingLink' => 'Training',
    'showStrategiesStrategyStatus' => 'Status',
    
    // strategyForm

    'strategyFormCreateTitle' => 'Create strategy',
    'strategyFormCreateHeader' => 'Create strategy',

    'strategyFormEditTitle' => 'Edit strategy # :id',
    'strategyFormEditHeader' => 'Edit strategy # :id',

    'strategyFormCreatePanelHeader' => 'Create strategy for tournament ":tournament"',
    'strategyFormEditPanelHeader' => 'Edit strategy # :id for tournament ":tournament"',

    'strategyFormNamePopover' => 'Name of strategy will be shown on strategies list. If given name is empty - default name with id reference will be applied',
    'strategyFormDescriptionPopover' => 'Description of strategy will be shown on strategies list if strategy will be correct',

    'strategyFormCompilerLabel' => 'Language',
    'strategyFormWayOfLoadingFile' => 'How to load',
    'strategyFormLoadFromFile' => 'Load strategy from file',
    'strategyFormLoadFromText' => 'Load strategy from text',


    'strategyFormStrategy' => 'strategy',
    'strategyFormName' => 'Name',
    'strategyFormDescription' => 'Description',
    'strategyFormSourceLabel' => 'Source',
    'strategyFormStrategyCodeLabel' => 'Code',


    // strategyProfile

    'strategyProfileTitle' => 'Strategy # :id',
    'strategyProfileHeader' => 'Strategy # :id',
    'strategyProfilePanelHeader' => 'Strategy # :id for tournament ":tournament"',

    'strategyProfileShowCode' => 'Show code',
    'strategyProfileHideCode' => 'Hide code',

    'strategyProfileErrorMessage' => 'Errors were found during compilation. You can check compilation log via "Show log" button',
    'strategyProfileShowError' => 'Show log',
    'strategyProfileHideError' => 'Hide log',

    'strategyProfileActualStrategy' => 'This is your actual strategy which will take part in tournament!',
    
    'strategyProfileMakeActualStrategy' => 'Set as active',
    'strategyProfilePanelFooterEdit' => 'strategy',


    // training

    'trainingTitle' => 'Trainings of tournament ":tournament"',
    'trainingHeader' => 'Trainings of tournament ":tournament"',
    'trainingResultHeader' => 'Results of tournament\'s trainings ":tournament"',
    'trainingCompetitionHeader' => 'Make training',
    
    'trainingStrategy' => 'Strategy :id',
    'trainingPlayer' => 'Player',
    'trainingAction' => 'Action',
    'trainingStartCompetition' => 'Play',
    'trainingDuelPlayer' => 'Player :number',
    'trainingViewGame' => 'View',

    // visualizeGame

    'visualizeGameTitle' => 'Visualization of ":game"',
    'visualizeGameHeader' => 'Game duel of ":game" between :user1 and :user2',

    'visualizeGameReset' => 'Reset',
    'visualizeGameStart' => 'Start',
    'visualizeGameStop' => 'Stop',
    'visualizeGameNextTurn' => 'Next turn',
    'visualizeGamePrevTurn' => 'Previous turn',
];
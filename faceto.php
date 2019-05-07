<?php
// 面對方向
function faceToCheck($nowPos, $nextPos)
{
	var faceTo : int = robot.placeData.faceTo;	
	
	var ft : int = faceTo;
	
	if (nextPos.x > nowPos.x && nextPos.y > nowPos.y) { 
        if (faceTo == 7 || faceTo == 9) turnFaceTo(faceTo - 6); }			// 面向下
	else if (nextPos.x < nowPos.x && nextPos.y < nowPos.y) { 
        if (faceTo == 1 || faceTo == 3) turnFaceTo(faceTo + 6); }		// 面向上
	else if (nextPos.x < nowPos.x && nextPos.y > nowPos.y) ft = 1; 				// 面向左
	else if (nextPos.x > nowPos.x && nextPos.y < nowPos.y) ft = 3; 			// 面向右
	else if (nextPos.x < nowPos.x && nextPos.y == nowPos.y) ft = 7; 		// 面向左上
	else if (nextPos.x == nowPos.x && nextPos.y > nowPos.y) ft = 1; 		// 面向左下
	else if (nextPos.x == nowPos.x && nextPos.y < nowPos.y) ft = 9; 		// 面向右上
	else if (nextPos.x > nowPos.x && nextPos.y == nowPos.y) ft = 3; 		// 面向右下
    else {ft = faceTo;}
    
	turnFaceTo( ft );
}

?>

1
9
3
3
1
1
1
7
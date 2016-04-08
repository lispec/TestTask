function validate(){

    var name=document.forms["form"]["name"].value;
    var email=document.forms["form"]["email"].value;
    var comment = document.forms["form"]["comment"].value;
    var check2=document.forms["form"]["check2"].value;

    var info = "*данное поле обязательно для заполнения";
    var info2 = "*email введен не верно";

    if (name.length==0){
        document.getElementById("nameV").innerHTML=info;
        return false;
    }

    if (email.length==0){
        document.getElementById("emailV").innerHTML=info;
        return false;
    }

    if (comment.length==0){
        document.getElementById("commentV").innerHTML=info;
        return false;
    }

    if (check2.length==0){
        document.getElementById("check2V").innerHTML=info;
        return false;
    }

    at=email.indexOf("@");
    dot=email.indexOf(".");

    if (at<1 || dot <1){
        document.getElementById("emailV").innerHTML=info2;
        return false;
    }
}
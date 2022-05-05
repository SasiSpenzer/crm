function getRange(mpID){
    var viewss = '';
    switch (true) {
        case (mpID < 6):
            viewss = '0%';
            break;
        case (mpID < 11):
            viewss = '19%';
            break;
        case (mpID < 16):
            viewss = '16%';
            break;
        case (mpID < 21):
            viewss = '45%';
            break;
        case (mpID < 26):
            viewss = '50%';
            break;
        case (mpID < 31):
            viewss = '61%';
            break;
        case (mpID < 36):
            viewss = '63%';
            break;
        case (mpID < 41):
            viewss = '70%';
            break;
        case (mpID < 46):
            viewss = '76%';
            break;
        case (mpID < 51):
            viewss = '75%';
            break;
        case (mpID < 56):
            viewss = '76%';
            break;
        case (mpID < 61):
            viewss = '75%';
            break;
        case (mpID < 66):
            viewss = '71%';
            break;
        case (mpID < 71):
            viewss = '77%';
            break;
        case (mpID < 76):
            viewss = '74%';
            break;
        case (mpID < 81):
            viewss = '80%';
            break;
        case (mpID < 86):
            viewss = '80%';
            break;
        case (mpID < 91):
            viewss = '84%';
            break;
        case (mpID < 96):
            viewss = '86%';
            break;
        case (mpID < 101):
            viewss = '83%';
            break;
        case (mpID < 106):
            viewss = 'below 12 %';
            break;
        case (mpID < 111):
            viewss = '86%';
            break;
        case (mpID < 116):
            viewss = '76%';
            break;
        case (mpID < 121):
            viewss = '89 %';
            break;
        case (mpID < 126):
            viewss = '92%';
            break;
        case (mpID < 131):
            viewss = '89%';
            break;
        case (mpID < 136):
            viewss = '82%';
            break;
        case (mpID < 141):
            viewss = '94%';
            break;
        case (mpID < 146):
            viewss = '94%';
            break;
        case (mpID < 151):
            viewss = '92%';
            break;
        case (mpID < 156):
            viewss = '88%';
            break;
        case (mpID < 161):
            viewss = '89%';
            break;
        case (mpID < 166):
            viewss = '87%';
            break;
        case (mpID < 171):
            viewss = '88%';
            break;
        case (mpID < 176):
            viewss = '87%';
            break;
        case (mpID < 181):
            viewss = '89%%';
            break;
        case (mpID < 186):
            viewss = '86%';
            break;
        case (mpID < 191):
            viewss = '87%';
            break;
        case (mpID < 196):
            viewss = '86%';
            break;
        case (mpID < 201):
            viewss = '92%';
            break;

        default:
            viewss = '100%';
            break;
    }
    return viewss;
}


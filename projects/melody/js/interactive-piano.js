const NUM_OCTAVES = 3;
const STARTING_OCTAVE = 3; // keyboard starts on C3
const KEY_PATTERN = [0,1,0,1,0,0,1,0,1,0,1,0];

// computer key to piano key mapping
const FULL_KEYMAP = ['ShiftLeft', 'KeyZ', 'KeyX', 'KeyC', 'KeyV', 'KeyB', 'KeyA', 'KeyS', 'KeyD', 'KeyF', 'KeyG', 'KeyH',
                     'KeyQ', 'KeyW', 'KeyE', 'KeyR', 'KeyT', 'KeyY', 'Digit1', 'Digit2', 'Digit3', 'Digit4', 'Digit5', 'Digit6',
                     'Digit7', 'Digit8', 'Digit9', 'Digit0', 'Minus', 'Equal', 'KeyU', 'KeyI', 'KeyO', 'KeyP', 'BracketLeft', 'BracketRight',
                     'KeyJ', 'KeyK', 'KeyL', 'Semicolon', 'Quote', 'Enter', 'KeyN', 'KeyM', 'Comma', 'Period', 'Slash', 'ShiftRight'];
const FULL_KEYMAP_CHAR = ['SL', 'Z', 'X', 'C', 'V', 'B', 'A', 'S', 'D', 'F', 'G', 'H',
                          'Q', 'W', 'E', 'R', 'T', 'Y', '1', '2', '3', '4', '5', '6',
                          '7', '8', '9', '0', '-', '=', 'U', 'I', 'O', 'P', '[', ']',
                          'J', 'K', 'L', ';', '"', 'EN', 'N', 'M', ',', '.', '/', 'SR'];

// reduced keymapping for 3 octaves
const KEYMAP = FULL_KEYMAP.slice(6,-5);
const KEYMAP_CHAR = FULL_KEYMAP_CHAR.slice(6, -5);

var keysPressed = [];
var intervals = [];

function renderKeyboard() {
    var keyboardHTML = "<ul class='keyboard'>\n";

    // each iteration is an octave
    for (var i = 0; i < NUM_OCTAVES; i++) {
        // each iteration is one note
        for (var j = 0; j < 12; j++) {
            const color = KEY_PATTERN[j] ? 'black' : 'white';
            keyboardHTML += "<li class='" + color + "-key'><p>" + KEYMAP_CHAR[i*12 + j] + "</p></li>\n";
        }
    }

    keyboardHTML += "<li class='white-key'><p>" + KEYMAP_CHAR[NUM_OCTAVES*12] + "</p></li>\n</ul>\n";

    return keyboardHTML;
}

function processPianoClick() {
    const keyIndex = $(this).index(); // get the pressed keyboard key
    if (keyIndex < 0 || keyIndex > NUM_OCTAVES * 12) return;
    playKeyAudio(keyIndex); // play audio

    addNote(keyIndex); // update notes history
}

function processComputerKeydown(e) {
    const keyIndex = KEYMAP.indexOf(e.code);
    if (keyIndex < 0 || keyIndex > NUM_OCTAVES * 12) return;
    playKeyAudio(keyIndex); // play audio

    addNote(keyIndex); // update notes history

    // change style of piano key
    const pianoKey = $('.keyboard').find('li').eq(keyIndex);
    if (pianoKey.hasClass('white-key')) pianoKey.addClass('white-key-active');
    else pianoKey.addClass('black-key-active');
}

function processComputerKeyup(e) {
    const keyIndex = KEYMAP.indexOf(e.code);
    if (keyIndex < 0 || keyIndex > NUM_OCTAVES * 12) return;

    // change style of piano key back to default
    const pianoKey = $('.keyboard').find('li').eq(keyIndex);
    if (pianoKey.hasClass('white-key')) pianoKey.removeClass('white-key-active');
    else pianoKey.removeClass('black-key-active');
}

// Helper function to play the audio clip for the pressed key
function playKeyAudio(keyIndex) {
    // Get key ID (D4, A5, Gb3, etc.)
    const allKeys = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
    const keyID = allKeys[keyIndex % 12] + Math.floor(STARTING_OCTAVE+keyIndex/12);

    // Play audio for the key
    var audio = new Audio('static/key_sounds/' + keyID + '.m4a');
    audio.play();
}

// Helper function to update arrays holding note history data
function addNote(keyIndex) {
    // update interval info if more than one key has been pressed
    if (keysPressed.length > 0) {
        const newInterval = keyIndex - keysPressed[keysPressed.length - 1];

        $('#intervals-display').append(newInterval + ', ');
        intervals.push(newInterval);
    }

    // either way, update notes info
    $('#notes-display').append(keyIndex + ', ');
    keysPressed.push(keyIndex);
}
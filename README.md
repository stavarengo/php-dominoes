tile:
- [x] holds two numbers

board:
- [ ] holds 28 tiles
- [ ] allows a player to draw one per time
- [ ] adds one tile to the board at the beginning of the game
- [ ] gives each player 7 random tiles at the beginning of the game
- [ ] print the current board status (draw the board)
- [ ] allow the player to take one of the following action: pass, draw or place a tile
- [ ] holds the board status: in progress, tide or winner

board status:
- [ ] holds the game status: in progress, tide or winner
- [ ] holds a reference to the next player, if it's in progress
- [ ] holds a reference to the winner, if the game has a winner
- [ ] holds no user if it's tide

board action validation
- [ ] Validates if the action taking place is valid.
  - [ ] is that user turn?
  - [ ] can he place that tile in that place?
  - [ ] is the game still on?
    
player brain:
- [ ] make a decision for a player to play in a board

brain decision:
- [ ] holds a action decision: draw, pass, place a tile
- [ ] convert the decision to a string

player:
- [ ] have a name
- [ ] holds a list of tile that they have in their hand
- [ ] choose a tile to play based on the board tiles
- [ ] ask the board for more tiles 

application:
- [ ] creates two players
- [ ] creates a board
- [ ] ask the board to start the game with that two players
- [ ] while the board status is still in progress,
    - [ ] ask the next player to make a decision
    - [ ] ask the board do take that decision
- [ ] print the game result


<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view notes index page
     */
    public function test_user_can_view_notes_index(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/notes');
        
        $response->assertOk();
        $response->assertViewIs('notes.index');
    }

    /**
     * Test guest cannot access notes index
     */
    public function test_guest_cannot_access_notes_index(): void
    {
        $response = $this->get('/notes');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test user can create a note successfully
     */
    public function test_user_can_create_note(): void
    {
        $user = User::factory()->create();

        $noteData = [
            'title' => 'Test Note Title',
            'content' => 'This is a test note content that contains some meaningful text.'
        ];

        $response = $this->actingAs($user)->post('/notes', $noteData);

        $response->assertRedirect('/notes');
        $response->assertSessionHas('success', 'Note created successfully!');

        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note Title',
            'content' => 'This is a test note content that contains some meaningful text.',
            'user_id' => $user->id
        ]);
    }

    /**
     * Test guest cannot create a note
     */
    public function test_guest_cannot_create_note(): void
    {
        $noteData = [
            'title' => 'Test Note',
            'content' => 'Test content'
        ];

        $response = $this->post('/notes', $noteData);
        
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('notes', ['title' => 'Test Note']);
    }

    /**
     * Test note creation validation - title required
     */
    public function test_note_creation_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/notes', [
            'content' => 'Valid content but no title'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Test note creation validation - content required
     */
    public function test_note_creation_requires_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/notes', [
            'title' => 'Valid title but no content'
        ]);

        $response->assertSessionHasErrors('content');
    }

    /**
     * Test note creation validation - title max length
     */
    public function test_note_title_cannot_exceed_100_characters(): void
    {
        $user = User::factory()->create();

        $longTitle = str_repeat('a', 101); // 101 characters

        $response = $this->actingAs($user)->post('/notes', [
            'title' => $longTitle,
            'content' => 'Valid content'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Test user can view their own note
     */
    public function test_user_can_view_their_own_note(): void
    {
        $user = User::factory()->create();
        
        // Create a note directly in database
        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'My Test Note',
            'content' => 'This is my test note content.'
        ]);

        $response = $this->actingAs($user)->get("/notes/{$note->id}");

        $response->assertOk();
        $response->assertViewIs('notes.show');
        $response->assertSee('My Test Note');
    }

    /**
     * Test user cannot view other user's note
     */
    public function test_user_cannot_view_other_users_note(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $note = Note::create([
            'user_id' => $user1->id,
            'title' => 'User 1 Note',
            'content' => 'This belongs to user 1.'
        ]);

        $response = $this->actingAs($user2)->get("/notes/{$note->id}");

        $response->assertForbidden();
    }

    /**
     * Test user can update their own note
     */
    public function test_user_can_update_their_own_note(): void
    {
        $user = User::factory()->create();
        
        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'content' => 'Original content.'
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content with new information.'
        ];

        $response = $this->actingAs($user)->put("/notes/{$note->id}", $updatedData);

        $response->assertRedirect('/notes');
        $response->assertSessionHas('success', 'Note updated successfully!');

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Title',
            'content' => 'Updated content with new information.',
            'user_id' => $user->id
        ]);
    }

    /**
     * Test user cannot update other user's note
     */
    public function test_user_cannot_update_other_users_note(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $note = Note::create([
            'user_id' => $user1->id,
            'title' => 'Protected Note',
            'content' => 'This should not be updated.'
        ]);

        $response = $this->actingAs($user2)->put("/notes/{$note->id}", [
            'title' => 'Hacked Title',
            'content' => 'Hacked content'
        ]);

        $response->assertForbidden();
    }

    /**
     * Test user can delete their own note
     */
    public function test_user_can_delete_their_own_note(): void
    {
        $user = User::factory()->create();
        
        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Note to Delete',
            'content' => 'This note will be deleted.'
        ]);

        $response = $this->actingAs($user)->delete("/notes/{$note->id}");

        $response->assertRedirect('/notes');
        $response->assertSessionHas('success', 'Note deleted successfully!');

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    /**
     * Test user cannot delete other user's note
     */
    public function test_user_cannot_delete_other_users_note(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $note = Note::create([
            'user_id' => $user1->id,
            'title' => 'Protected Note',
            'content' => 'This should not be deleted.'
        ]);

        $response = $this->actingAs($user2)->delete("/notes/{$note->id}");

        $response->assertForbidden();

        // Verify the note still exists
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Protected Note'
        ]);
    }

    /**
     * Test search functionality works correctly
     */
    public function test_search_functionality(): void
    {
        $user = User::factory()->create();
        
        Note::create([
            'user_id' => $user->id,
            'title' => 'Laravel Tutorial',
            'content' => 'Learning PHP framework'
        ]);
        
        Note::create([
            'user_id' => $user->id,
            'title' => 'JavaScript Guide',
            'content' => 'Frontend development tips'
        ]);

        // Search by title
        $response = $this->actingAs($user)->get('/notes?search=Laravel');
        $response->assertOk();
        $response->assertSee('Laravel Tutorial');
        $response->assertDontSee('JavaScript Guide');
    }

    /**
     * Test pagination works correctly
     */
    public function test_pagination_works(): void
    {
        $user = User::factory()->create();
        
        // Create 12 notes (more than the default 9 per page)
        for ($i = 1; $i <= 12; $i++) {
            Note::create([
                'user_id' => $user->id,
                'title' => "Note {$i}",
                'content' => "Content for note {$i}."
            ]);
        }

        $response = $this->actingAs($user)->get('/notes');
        
        $response->assertOk();
        // Should see pagination links when there are more than 9 notes
        $response->assertSee('Next');
    }

    /**
     * Test user only sees their own notes
     */
    public function test_user_only_sees_their_own_notes(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Note::create([
            'user_id' => $user1->id,
            'title' => 'User 1 Note',
            'content' => 'This belongs to user 1.'
        ]);
        
        Note::create([
            'user_id' => $user2->id,
            'title' => 'User 2 Note',
            'content' => 'This belongs to user 2.'
        ]);

        // User 1 should only see their note
        $response = $this->actingAs($user1)->get('/notes');
        $response->assertOk();
        $response->assertSee('User 1 Note');
        $response->assertDontSee('User 2 Note');
    }

    /**
     * Test notes ordering (latest first)
     */
    public function test_notes_are_ordered_latest_first(): void
    {
        $user = User::factory()->create();
        
        // Create first note
        Note::create([
            'user_id' => $user->id,
            'title' => 'First Note',
            'content' => 'This is the first note.'
        ]);
        
        // Wait a moment to ensure different timestamps
        sleep(1);
        
        // Create second note
        Note::create([
            'user_id' => $user->id,
            'title' => 'Second Note',
            'content' => 'This is the second note.'
        ]);

        $response = $this->actingAs($user)->get('/notes');
        $response->assertOk();
        
        // The latest note (Second Note) should appear before the earlier note (First Note)
        $content = $response->getContent();
        $secondNotePos = strpos($content, 'Second Note');
        $firstNotePos = strpos($content, 'First Note');
        
        $this->assertTrue($secondNotePos < $firstNotePos, 'Latest note should appear first in the list');
    }

    /**
     * Test complete CRUD workflow
     */
    public function test_complete_notes_crud_workflow(): void
    {
        $user = User::factory()->create();

        // 1. CREATE - User can create a note
        $response = $this->actingAs($user)->post('/notes', [
            'title' => 'CRUD Test Note',
            'content' => 'Testing complete CRUD workflow.'
        ]);
        
        $response->assertRedirect('/notes');
        $this->assertDatabaseHas('notes', ['title' => 'CRUD Test Note']);
        
        $note = Note::where('title', 'CRUD Test Note')->first();

        // 2. READ - User can view the note
        $response = $this->actingAs($user)->get("/notes/{$note->id}");
        $response->assertOk();
        $response->assertSee('CRUD Test Note');
        $response->assertSee('Testing complete CRUD workflow.');

        // 3. UPDATE - User can update the note
        $response = $this->actingAs($user)->put("/notes/{$note->id}", [
            'title' => 'Updated CRUD Test Note',
            'content' => 'Updated content for CRUD workflow.'
        ]);
        
        $response->assertRedirect('/notes');
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated CRUD Test Note',
            'content' => 'Updated content for CRUD workflow.'
        ]);

        // 4. DELETE - User can delete the note
        $response = $this->actingAs($user)->delete("/notes/{$note->id}");
        $response->assertRedirect('/notes');
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}